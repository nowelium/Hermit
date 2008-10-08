<?php

/**
 * @author nowelium
 */
class HermitObjectProxy implements HermitFutureProxy {
    protected $context;
    protected $target;
    protected $annote;
    protected $commandFactory;
    protected function __construct(HermitContext $ctx, ReflectionClass $reflector, $target){
        $this->context = $ctx;
        $this->target = $target;
        $this->annote = HermitAnnote::create($reflector);
        $this->commandFactory = new HermitSqlCommandFactory($ctx, $reflector);
    }
    public static function delegate(HermitContext $ctx, ReflectionClass $reflector, $instance = null){
        return new self($ctx, $reflector, $instance);
    }
    public function request($name, array $params){
        if($this->annote->hasMethod($name)){
            $method = $this->annote->getMethod($name);
            return $method->invokeArgs($this->target, $params);
        }
        $command = $this->commandFactory->create($name);
        return $command->execute($pdo, $params);
    }
}
