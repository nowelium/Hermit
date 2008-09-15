<?php

class HermitResponder {
    private $target;
    private $methodName;
    public function __construct($target, $methodName){
        $this->target = $target;
        $this->methodName = $methodName;
    }
    public function respond($response){
        return call_user_func_array($this->target, $this->methodName, array($response));
    }
}
