<?php

/**
 * @author nowelium
 */
class HermitClassProxy implements HermitFutureProxy {
    private $context;
    private $reflector;
    protected function __construct(HermitContext $ctx, ReflectionClass $reflector){
        $this->context = $ctx;
        $this->reflector = $reflector;
    }
    public static function delegate(HermitContext $ctx, ReflectionClass $reflector, $instance = null){
        return new self($ctx, $reflector);
    }
    public function request($name, array $params){
        throw new RuntimeException('T.B.D');
    }
}
