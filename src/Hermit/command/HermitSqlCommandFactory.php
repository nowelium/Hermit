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
        if(isset($this->createdCommands[$methodName])){
            return $this->createdCommands[$methodName];
        }
        $method = $this->annote->getMethod($methodName);
        $command = $this->createCommand($pdo, $method);
        return $this->createdCommands[$methodName] = $command;
    }
    protected function createCommand(PDO $pdo, ReflectionMethod $method){
        $methodName = $method->getName();
        if($this->annote->isProcedureMethod($method)){
            return $this->createProcedureCommand($pdo, $method);
        }
        if($this->annote->isInsertMethod($method)){
            return $this->createInsertCommand($pdo, $method);
        }
        if($this->annote->isUpdateMethod($method)){
            return $this->createUpdateCommand($pdo, $method);
        }
        if($this->annote->isDeleteMethod($method)){
            return $this->createDeleteCommand($pdo, $method);
        }
        return $this->createSelectCommand($pdo, $method);
    }
    protected function createProcedureCommand(PDO $pdo, ReflectionMethod $method){
        $dbName = HermitDatabaseMetaFactory::getDatabaseName($pdo);
        $creator = $this->createProcedureSqlCreator($method, $dbName);
        $creator->initialize($pdo, $method, $this->annote);
        return new HermitProcedureCommand($method, $creator);
    }
    protected function createProcedureSqlCreator(ReflectionMethod $method, $dbName){
        $sql = $this->annote->getProcedure($method);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        $sql = $this->annote->getSql($method, $dbName);
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
    protected function createSelectCommand(PDO $pdo, ReflectionMethod $method){
        $dbName = HermitDatabaseMetaFactory::getDatabaseName($pdo);
        $creator = $this->createSelectSqlCreator($method, $dbName);
        $creator->initialize($pdo, $method, $this->annote);
        if($creator instanceof HermitAppendableSqlCreator){
            $this->appendSql($method, $creator);
        }
        $valueType = HermitValueTypeFactory::create($this->annote, $method);
        return new HermitSelectCommand($method, $creator, $valueType);
    }
    protected function createSelectSqlCreator(ReflectionMethod $method, $dbName){
        $sql = $this->annote->getSql($method, $dbName);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        $sql = $this->annote->getFile($method, $dbName);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        return new HermitAutoSelectSqlCreator;
    }
    protected function appendSql(ReflectionMethod $method, HermitAppendableSqlCreator $creator){
        $query = $this->annote->getQuery($method);
        if(null !== $query){
            $creator->addQuery($query);
        }
    }
}
