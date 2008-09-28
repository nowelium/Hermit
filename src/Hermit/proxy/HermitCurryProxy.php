<?php

/**
 * @author nowelium
 */
class HermitCurryProxy implements HermitProxy {
    private $targetClass;
    private $methodName;
    private $parameters;
    public function setClass($class){
        $this->targetClass = $class;
    }
    public function setMethodName($methodName){
        $this->methodName = $methodName;
    }
    public function setParameters(array $parameters){
        $this->parameters = $parameters;
    }
    public function request($name, array $parameters){
        if(null === $this->targetClass){
            throw new BadMethodCallException(__CLASS__ . '::' . $name);
        }
        $callName = $this->methodName;
        if(null === $callName){
            $callName = $name;
        }
        $callParameters = $this->parameters;
        if(null === $parameters){
            $callParameters = $parameters;
        }
        return call_user_func_array(array($this->targetClass, $methodName), $parameters);
    }
}
