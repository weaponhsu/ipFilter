<?php


namespace src\Providers;


use src\Core\Container;
use src\Functions\emailTools;
use src\Interfaces\Provider;

class emailProviders implements Provider
{

    public function serviceProvider(Container $container)
    {
        // TODO: Implement serviceProvider() method.
        $container['emailTools'] = function ($container){
            return new emailTools($container);
        };
    }
}
