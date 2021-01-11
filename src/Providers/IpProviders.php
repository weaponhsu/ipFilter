<?php


namespace src\Providers;


use src\Core\Container;
use src\Interfaces\Provider;
use src\Functions\ipTools;

class IpProviders implements Provider
{
    public function __construct()
    {
        echo __CLASS__ . "被实例化了\r\n";
    }

    public function serviceProvider(Container $container)
    {
        // TODO: Implement serviceProvider() method.

        echo "ipProvider被注册了\r\n";
        $container['ipTools'] = function ($container){
            echo "实例化ipTools\r\n";
            return new ipTools($container);
        };
    }
}
