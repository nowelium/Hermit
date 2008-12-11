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
        $pdo = HermitDataSourceManager::get($this->context->getName(), $methodName, HermitEvent::EVT_SETUP);
        if(null === $pdo){
            throw new RuntimeException('setup connection fail. pdo: ' . $pdo);
        }
        
        if(HermitNamingUtils::isProcedure($methodName)){
            $factory = new HermitProcedureSqlCommandFactory;
        } else if(HermitNamingUtils::isInsert($methodName)){
            $factory = new HermitInsertSqlCommandFactory;
        } else if(HermitNamingUtils::isUpdate($methodName)){
            $factory = new HermitUpdateSqlCommandFactory;
        } else if(HermitNamingUtils::isDelete($methodName)){
            $factory = new HermitDeleteSqlCommandFactory;
        } else {
            $factory = new HermitSelectSqlCommandFactory;
        }
        $factory->setAnnote($this->annote);
        $factory->setMethod($method);
        return $factory->create($pdo, $this->context);
    }
}
