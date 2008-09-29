<?php

/**
 * @author nowelium
 */
class HermitCallableProxy implements HermitProxy {
    private $proxy;
    private $call = array();
    public function __construct(HermitProxy $proxy, array $callable){
        $this->proxy = $proxy;
        $this->call = $callable;
    }
    public function request($name, array $parameters){
        return call_user_func($this->call, $this->proxy, $name, $parameters);
    }
}
