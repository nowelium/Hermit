<?php

/**
 * @author nowelium
 */
class HermitSqlCommandFactory {

    protected $context;
    protected $annote;
    protected $reflector;
    protected $createdCommands = array();

    public function __construct(HermitContext $ctx, ReflectionClass $reflector){
        $this->context = $ctx;
        $this->annote = HermitAnnote::create($reflector);
        $this->reflector = $reflector;
    }
    public function has($methodName){
        return $this->annote->hasMethod($methodName);
    }
    public function create($methodName){
        if(isset($this->createdCommands[$methodName])){
            return $this->createdCommands[$methodName];
        }
        $method = $this->annote->getMethod($methodName);
        $command = $this->createCommand($method);
        return $this->createdCommands[$methodName] = $command;
    }
    protected function createCommand(ReflectionMethod $method){
        $methodName = $method->getName();
        $pdo = HermitDataSourceManager::get($this->context->getTargetClass(), $methodName, HermitEvent::EVT_SETUP);
        if(null === $pdo){
            throw new RuntimeException('setup connection fail. pdo: ' . $pdo);
        }
        
        if($this->annote->isProcedureMethod($method)){
            $factory = new HermitProcedureSqlCommandFactory;
        } else if($this->annote->isInsertMethod($method)){
            $factory = new HermitInsertSqlCommandFactory;
        } else if($this->annote->isUpdateMethod($method)){
            $factory = new HermitUpdateSqlCommandFactory;
        } else if($this->annote->isDeleteMethod($method)){
            $factory = new HermitDeleteSqlCommandFactory;
        } else {
            $factory = new HermitSelectSqlCommandFactory;
        }
        $factory->setAnnote($this->annote);
        $factory->setMethod($method);
        return $factory->create($pdo, $this->context);
    }
}
