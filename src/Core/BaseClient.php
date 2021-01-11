<?php


namespace src\Core;


class BaseClient
{
    public $app;

    public function __construct(Container $container)
    {
        $this->app = $container;
    }

}
