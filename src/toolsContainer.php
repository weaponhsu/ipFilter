<?php


namespace src;


use src\Core\BaseContainer;
use src\Providers\IpProviders;
use src\Providers\emailProviders;
use src\Providers\Providers;

class toolsContainer extends BaseContainer
{
    protected $provider = [
        // 服务提供者
        emailProviders::class,
        IpProviders::class
    ];


    public function __construct($params = array())
    {
        echo "toolsContainer被实例化了\r\n";
        parent::__construct($params);
    }
}
