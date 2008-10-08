<?php

/**
 * @author nowelium
 */
class HermitObjectProxy implements HermitFutureProxy {
    protected $context;
    protected $target;
    protected $annote;
    protected function __construct(HermitContext $ctx, ReflectionClass $reflector, $target){
        $this->context = $ctx;
        $this->target = $target;
        $this->annote = HermitAnnote::create($reflector);
    }
    public static function delegate(HermitContext $ctx, ReflectionClass $reflector, $instance = null){
        return new self($ctx, $reflector, $instance);
    }
    public function request($name, array $params){
        if($this->annote->hasMethod($name)){
            $method = $this->annote->getMethod($name);
            return $method->invokeArgs($this->target, $params);
        }
        $pdo = HermitDataSourceManager::get($this->reflector->getName());
        $command = $this->commandFactory->create($pdo, $name);
        return $command->execute($pdo, $params);
    }
}
