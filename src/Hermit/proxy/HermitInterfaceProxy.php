<?php

/**
 * @author nowelium
 */
class HermitInterfaceProxy implements HermitFutureProxy {
    protected $reflector;
    protected $commandFactory;
    protected function __construct(ReflectionClass $reflector){
        $this->reflector = $reflector;
        $this->commandFactory = new HermitSqlCommandFactory($reflector);
    }
    public static function delegate(ReflectionClass $reflector, $instance = null){
        return new self($reflector);
    }
    public function request($name, array $params){
        if(!$this->commandFactory->hasCommand($name)){
            throw new BadMethodCallException($this->reflector->getName() . '::' . $name);
        }
        $command = $this->commandFactory->getCommand($name);
        return $command->execute($pdo, $params);
    }
}
