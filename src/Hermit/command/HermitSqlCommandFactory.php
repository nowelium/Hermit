<?php

/**
 * @author nowelium
 */
class HermitSqlCommandFactory {
    protected $annote;
    protected $reflector;
    protected $createdCommands = array();
    public function __construct(ReflectionClass $reflector){
        $this->annote = HermitAnnote::create($reflector);
        $this->reflector = $reflector;
    }
    public function hasCommand($methodName){
        return $this->annote->hasMethod($methodName);
    }
    public function getCommand($methodName){
        $method = $this->annote->getMethod($methodName);
        $methodId = spl_object_hash($method);
        if(isset($this->createdCommands[$methodId])){
            return $this->createdCommands[$methodId];
        }
        $command = $this->createCommand($method);
        return $this->createdCommands[$methodId] = $command;
    }
    protected function createCommand(ReflectionMethod $method){
        if($this->annote->isInsertMethod($methodName)){
            return $this->createInsertCommand($method);
        }
        if($this->annote->isUpdateMethod($methodName)){
            return $this->createUpdateCommand($method);
        }
        if($this->annote->isDeleteMethod($methodName)){
            return $this->createDeleteCommand($method);
        }
        return $this->createSelectCommand($method);
    }
    protected function createInsertCommand(ReflectionMethod $method){
    }
    protected function createUpdateCommand(ReflectionMethod $method){
    }
    protected function createSelectCommand(ReflectionMethod $method){
        $creator = $this->createSelectSqlCreator($method->getName());
        return new HermitSelectCommand($method, $creator);
    }
    protected function createSelectSqlCreator($name){
        switch(true){
        case $this->annote->hasSql($name):
            return new HermitStaticSqlCreator($this->annote->getSql($name));
        case $this->annote->hasFile($name):
            return new HermitStaticSqlCreator($this->annote->getFile($name));
        case $this->annote->hasPath($name):
            return new HermitStaticSqlCreator($this->annote->getPath($name));
        case $this->annote->hasQuery($name):
            $creator = new HermitAutoSelectCreator;
            $creator->addQuery($this->annote->getQuery());
            return $creator;
        }
        throw new BadMethodCallException('invalid method:' . $name);
    }
}
