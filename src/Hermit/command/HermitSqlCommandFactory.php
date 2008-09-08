<?php

class HermitSqlCommandFactory {
    protected $annote;
    protected $reflector;
    protected $hasCommand = array();
    public function __construct(ReflectionClass $reflector){
        $this->annote = HermitAnnote::create($reflector);
        $this->reflector = $reflector;
    }
    public function hasCommand($methodName){
        return $this->annote->hasMethod($methodName);
    }
    public function getCommand($methodName){
        $command = new HermitSqlCommand;

        return $command;
    }
}
