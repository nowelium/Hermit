<?php

/**
 * @author nowelium
 */
class HermitClassProxy implements HermitFutureProxy {
    private $reflector;
    protected function __construct(ReflectionClass $reflector){
        $this->reflector = $reflector;
    }
    public static function delegate(ReflectionClass $reflector, $instance = null){
        return new self($reflector);
    }
    public function request($name, array $params){
    }
}
