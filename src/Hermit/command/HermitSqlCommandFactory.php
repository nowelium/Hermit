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
    public function has($methodName){
        return $this->annote->hasMethod($methodName);
    }
    public function create(PDO $pdo, $methodName){
        $method = $this->annote->getMethod($methodName);
        $methodId = spl_object_hash($method);
        if(isset($this->createdCommands[$methodId])){
            return $this->createdCommands[$methodId];
        }
        $command = $this->createCommand($method);
        return $this->createdCommands[$methodId] = $command;
    }
    protected function createCommand(ReflectionMethod $method){
        $methodName = $method->getName();
        if($this->annote->isProcedureMethod($methodName)){
            return $this->createProcedureCommand($method);
        }
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
    protected function createProcedureCommand(ReflectionMethod $method){
        $creator = $this->createProcedureSqlCreator($method);
        return new HermitProcedureCommand($method, $creator);
    }
    protected function createProcedureSqlCreator(ReflectionMethod $method){
        $sql = $this->annote->getProcedure($method);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        $sql = $this->annote->getSql($method);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        $sql = $this->annote->getFile($method);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        throw new BadMethodCallException('method: "' . $method->getName() . '" was not apply to Procedure command');
    }
    protected function createInsertCommand(ReflectionMethod $method){
        throw new RuntimeException('T.B.D');
    }
    protected function createUpdateCommand(ReflectionMethod $method){
        throw new RuntimeException('T.B.D');
    }
    protected function createDeleteCommand(ReflectionMethod $method){
        throw new RuntimeException('T.B.D');
    }
    protected function createSelectCommand(ReflectionMethod $method){
        $creator = $this->createSelectSqlCreator($method);
        $query = $this->annote->getQuery($method);
        if(null !== $query){
            $creator->addQuery($query);
        }
        return new HermitSelectCommand($method, $creator);
    }
    protected function createSelectSqlCreator(ReflectionMethod $method){
        $sql = $this->annote->getSql($method);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        $sql = $this->annote->getFile($method);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        return new HermitAutoSelectCreator;
    }
}

