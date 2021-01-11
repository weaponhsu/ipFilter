<?php


namespace src\Core;


use src\Interfaces\Provider;

class Container implements \ArrayAccess
{
    /**
     * 中间件
     * @var array
     */
    protected $middleWares = [];
    private $instances =[];
    private $values = [];
    public $register;

    public function serviceRegister(Provider $provider)
    {
        echo "provider被注册了\r\n";
        $provider->serviceProvider($this);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    public function offsetGet($offset)
    {
        echo $offset . "offsetGet\r\n";
        if(isset($this->instances[$offset])){
            return $this->instances[$offset];
        }
        $raw = $this->values[$offset];
        $val = $this->values[$offset] = $raw($this);
        $this->instances[$offset] = $val;
        return $val;
    }


    public function offsetSet($offset, $value)
    {
        $this->values[$offset] = $value;
    }

    public function offsetUnset($offset)
    {

    }

    /**
     * @return array
     */
    public function getMiddleWares()
    {
        return $this->middleWares;
    }

    /**
     * @param array $middleWares
     */
    public function setMiddleWares($middleWares)
    {
        $this->middleWares = $middleWares;
    }

    /**
     * 添加中间件
     * @param $class_and_function
     * @param string $name
     * @return array
     */
    public function pushMiddleWares($class_and_function, $name =''){
        if(empty($this->middleWares)){
            $this->middleWares[$name] = $class_and_function;
        }else{
            array_push($this->middleWares,[$name=>$class_and_function]);
        }
        return $this->middleWares;
    }
}