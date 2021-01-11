<?php


namespace src\Functions;


use src\Core\BaseClient;
use src\Core\Container;

class emailTools extends BaseClient
{
    public function __construct(Container $container)
    {
        echo "emailTools被实例化\r\n";
        parent::__construct($container);
    }

    /**
     * 校验邮箱有效性
     * @param string $email
     * @return bool
     * @throws \Exception
     */
    public function emailIsValid($email = ''){
        $email = !empty($email) && is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL) ?
            trim($email) : '';

        if (empty($email))
            throw new \Exception("email地址不能为空", 400);

        $email_arr = explode("@", $email);
        $res = checkdnsrr($email_arr, "MX");

        if ($res === false)
            throw new \Exception("无效email地址", 400);

        return true;
    }


}
