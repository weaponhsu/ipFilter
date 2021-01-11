<?php


namespace src\Functions;


use src\Core\BaseClient;
use src\Core\Container;


class ipTools extends BaseClient
{
    /**
     * 获取ip段
     * @param $ip string 0.0.0.0/n
     * @return array array(start_ip, end_ip)
     * @throws \Exception
     */
    public function genIp($ip) {
        list($ip_address, $length) = explode('/', $ip);

        if (empty($ip_address) || empty($length))
            throw new \Exception("IP Address Empty");
        if ( !preg_match('/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/', $ip_address))
            throw new \Exception("IP Address Error");

        // 对数的底数(二进制)
        $base = 2;
        // ip段的长度
        $size = 8;
        $ip_address_arr = explode('.', $ip_address);
        $ip_address_array = [];
        array_walk($ip_address_arr, function($value, $idx)  use (&$ip_address_array, &$size) {
            array_push($ip_address_array, strlen(decbin($value)) < $size ? str_pad(decbin($value), $size, '0', STR_PAD_LEFT) : decbin($value));
        });
        // 倒置ip 方便计算
        $ip_address_array = array_reverse($ip_address_array);

        $exp = 32 - $length;
        // 0和255这两个不能用
        $ip = pow($base, $exp) - 2;
        var_dump($ip);
        $ip = decbin($ip);

        $subnet_mask_arr = [];
        // 若ip段长度只为254
        if (ceil(strlen($ip) / $size) == 1) {
            array_push($subnet_mask_arr, $ip);
            $subnet_mask_len = 4 - count($subnet_mask_arr);
        } else {
            for ($i = 0; $i < ceil(strlen($ip) / $size); $i++) {
                $str = substr($ip, $i * $size, $size);
                $len = strlen($str);

                if ($len != $size)
                    $str = str_pad($str, $size, '1', STR_PAD_LEFT);

                array_push($subnet_mask_arr, $str);
            }
            $subnet_mask_len = 4 - count($subnet_mask_arr);
        }
        for ($j = 0; $j < $subnet_mask_len; $j++) {
            array_push($subnet_mask_arr, decbin(255));
        }

        $end_ip = $start_ip = [];
        $total_len = 0;
        // 子网掩码和ip 与运算
        foreach ($subnet_mask_arr as $idx => $pos) {
            // ip总长度
            $total_len += strlen($pos);
            // ip个数为254
            if ($total_len - $exp < 0) {
                array_push($end_ip, $idx == 0 ? bindec($pos | $ip_address_array[$idx]) - 1 : bindec($pos | $ip_address_array[$idx]));
                array_push($start_ip, $idx == 0 ? bindec($pos & $ip_address_array[$idx]) + 1 : bindec($pos & $ip_address_array[$idx]));
            }
            // ip个数在为254-262142
            else if ($exp - $total_len == 0) {
                array_push($end_ip, bindec($pos | $ip_address_array[$idx]) + 1);
                array_push($start_ip, $idx == 0 ? bindec($pos & $ip_address_array[$idx]) + 1 : bindec($pos & $ip_address_array[$idx]));
            }
            else if ($total_len - $exp > 0) {
                array_push($end_ip, bindec(str_pad(substr($ip_address_array[$idx], 0, $total_len - $exp), $size, 1, STR_PAD_RIGHT)));
                array_push($start_ip, bindec($ip_address_array[$idx]));
            }
        }

        // [start_ip, end_ip]
        return [
            implode('.', array_reverse($start_ip)),
            implode('.', array_reverse($end_ip))
        ];
    }

    /**
     * 获取IP
     * @return array|false|null|string
     */
    public function getIP()
    {
        $real_ip = null;
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $real_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $real_ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $real_ip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $real_ip = getenv("HTTP_X_FORWARDED_FOR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $real_ip = getenv("HTTP_CLIENT_IP");
            } else {
                $real_ip = getenv("REMOTE_ADDR");
            }
        }
        return $real_ip ? $real_ip : '';
    }
}
