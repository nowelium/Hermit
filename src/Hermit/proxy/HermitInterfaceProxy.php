<?php

/**
 * @author nowelium
 */
class HermitInterfaceProxy implements HermitFutureProxy {
    protected $context;
    protected $reflector;
    protected $commandFactory;
    protected function __construct(HermitContext $ctx, ReflectionClass $reflector){
        $this->context = $ctx;
        $this->reflector = $reflector;
        $this->commandFactory = new HermitSqlCommandFactory($ctx, $reflector);
    }
    public static function delegate(HermitContext $ctx, ReflectionClass $reflector, $instance = null){
        return new self($ctx, $reflector);
    }
    public function request($name, array $params){
        if(!$this->commandFactory->has($name)){
            throw new BadMethodCallException($this->reflector->getName() . '::' . $name);
        }
        $command = $this->commandFactory->create($this->context, $name);
        return $command->execute($params);
    }
}
