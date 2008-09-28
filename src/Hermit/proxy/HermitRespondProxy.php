<?php

/**
 * @author nowelium
 */
class HermitRespondProxy implements HermitProxy {
    private $target;
    private $methodName;
    public function __construct($target, $methodName){
        $this->target = $target;
        $this->methodName = $methodName;
    }
    public function request($name, array $response){
        return call_user_func_array(array($this->target, $this->methodName), $response);
    }
}
