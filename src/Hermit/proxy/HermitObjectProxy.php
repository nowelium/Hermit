<?php

/**
 * @author nowelium
 */
class HermitObjectProxy implements HermitFutureProxy {
    protected $target;
    protected $annote;
    protected function __construct(ReflectionClass $reflector, $target){
        $this->target = $target;
        $this->annote = HermitAnnote::create($reflector);
    }
    public static function delegate(ReflectionClass $reflector, $instance = null){
        return new self($reflector, $instance);
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
