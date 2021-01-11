<?php


namespace src\Interfaces;


use src\Core\Container;

interface Provider
{
    public function serviceProvider(Container $container);

}
