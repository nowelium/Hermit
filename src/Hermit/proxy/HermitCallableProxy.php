<?php

/**
 * @author nowelium
 */
class HermitCallableProxy implements HermitProxy {
    protected $proxy;
    protected $target;
    protected $methodName;
    public function __construct(HermitProxy $proxy, $target, $methodName){
        $this->proxy = $proxy;
        $this->target = $target;
        $this->methodName = $methodName;
    }
    public function request($name, array $parameters){
        $callable = array($this->target, $this->methodName);
        return call_user_func($callable, $this->proxy, $name, $parameters);
    }
}
