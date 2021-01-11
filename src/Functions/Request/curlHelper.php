<?php


namespace src\Functions\Request;


class curlHelper
{
    public function curlRequest($base_url, $data, $method = 'get', $ssl = true, $exe_timeout = 10,
                                $conn_timeout = 10, $dns_timeout = 3600)
    {

        $ch = curl_init();

        if ($method == 'get') {
            //method get
            if ( !empty($data) && is_array($data)){
                $connect_symbol = strpos($base_url, '?') !== false ? '&' : '?';
                foreach($data as $key => $val) {
                    if ( is_array($val) ) {
                        $val = serialize($val);
                    }
                    $base_url .= $connect_symbol . $key . '=' . rawurlencode($val);
                    $connect_symbol = '&';
                }
            }
        } else {
            if ( !empty($data) && is_array($data)){
                foreach($data as $key => $val) {
                    if($key=='imageData'){
                        if (file_exists($val)) {
                            $data[$key] = new \CURLFile($val);
                        }
                    }
                    if (is_array($val)) {
                        $data[$key] = serialize($val);
                    }
                }
            }
            //method post
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_URL, $base_url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $conn_timeout);
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, $dns_timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $exe_timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        // 关闭ssl验证
        if($ssl){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $output = curl_exec($ch);

        if ($output === false)
            $output = '';

        curl_close($ch);
        return $output;
    }

}
