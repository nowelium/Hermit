<?php

/**
 * @author nowelium
 */
class HermitDelegator implements HermitProxy {
    protected $target;
    protected $methodName;
    public function __construct($target, $methodName){
        $this->target = $target;
        $this->methodName = $methodName;
    }
    public function request($name, array $parameters){
        return call_user_func_array(array($this->target, $this->methodName), array($parameters));
    }
}
