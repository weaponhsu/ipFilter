<?php


namespace src\Core;

use src\Functions\ipTools;
use src\Functions\emailTools;
use src\Providers\ProvidersBase;


class BaseContainer extends Container
{
    public $params = [];
    protected $provider = [];

    public function __construct($params = [])
    {
        // 注册
        array_walk($this->provider, function ($provider) {
            $obj = new $provider;
            $this->serviceRegister($obj);
        });
    }

    public function __get($id) {
        return $this->offsetGet($id);
    }
}
