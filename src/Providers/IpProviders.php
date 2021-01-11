<?php


namespace src\Providers;


use src\Core\Container;
use src\Interfaces\Provider;
use src\Functions\ipTools;

class IpProviders implements Provider
{

    public function serviceProvider(Container $container)
    {
        // TODO: Implement serviceProvider() method.

        $container['ipTools'] = function ($container){
            return new ipTools($container);
        };
    }
}
