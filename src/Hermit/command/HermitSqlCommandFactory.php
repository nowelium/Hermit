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
        $dbms = HermitDatabaseMetaFactory::getDbms($pdo);
        $creator = $this->createProcedureSqlCreator($method, $dbms);
        $creator->initialize($pdo, $method, $this->annote);
        $valueType = HermitValueTypeFactory::create($this->annote, $method);

        $command = new HermitProcedureCommand;
        $command->setContext($this->context);
        $command->setMethod($method);
        $command->setSqlCreator($creator);
        $command->setValueType($valueType);
        $command->setAnnote($this->annote);
        return $command;
    }
    protected function createProcedureSqlCreator(ReflectionMethod $method, $dbName){
        $sql = $this->annote->getProcedure($method);
        if(null !== $sql){
            return new HermitProcedureCallSqlCreator($sql);
        }
        $sql = $this->annote->getSql($method, $dbName);
        if(null !== $sql){
            return new HermitProcedureCallSqlCreator($sql);
        }
        $sql = $this->annote->getFile($method);
        if(null !== $sql){
            return new HermitProcedureCallSqlCreator($sql);
        }
        throw new BadMethodCallException('method: "' . $method->getName() . '" was not apply to Procedure command');
    }
    protected function createInsertCommand(PDO $pdo, ReflectionMethod $method){
        $dbms = HermitDatabaseMetaFactory::getDbms($pdo);
        $creator = $this->createInsertSqlCreator($method, $dbms);
        $creator->initialize($pdo, $method, $this->annote);
        $valueType = HermitValueTypeFactory::create($this->annote, $method);
        $command = new HermitInsertCommand;
        $command->setContext($this->context);
        $command->setMethod($method);
        $command->setSqlCreator($creator);
        $command->setValueType($valueType);
        return $command;
    }
    protected function createInsertSqlCreator($method, $dbms){
        $sql = $this->annote->getSql($method, $dbms);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        $sql = $this->annote->getFile($method, $dbms);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        return new HermitAutoInsertSqlCreator;
    }
    protected function createUpdateCommand(PDO $pdo, ReflectionMethod $method){
        $dbms = HermitDatabaseMetaFactory::getDbms($pdo);
        $creator = $this->createUpdateSqlCreator($method, $dbms);
        $creator->initialize($pdo, $method, $this->annote);
        $valueType = HermitValueTypeFactory::create($this->annote, $method);
        $command = new HermitUpdateCommand;
        $command->setContext($this->context);
        $command->setMethod($method);
        $command->setSqlCreator($creator);
        $command->setValueType($valueType);
        return $command;
    }
    protected function createUpdateSqlCreator($method, $dbms){
        $sql = $this->annote->getSql($method, $dbms);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        $sql = $this->annote->getFile($method, $dbms);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        return new HermitAutoUpdateSqlCreator;
    }
    protected function createDeleteCommand(PDO $pdo, ReflectionMethod $method){
        $dbms = HermitDatabaseMetaFactory::getDbms($pdo);
        $creator = $this->createDeleteSqlCreator($method, $dbms);
        $creator->initialize($pdo, $method, $this->annote);
        $valueType = HermitValueTypeFactory::create($this->annote, $method);
        $command = new HermitDeleteCommand;
        $command->setContext($this->context);
        $command->setMethod($method);
        $command->setSqlCreator($creator);
        $command->setValueType($valueType);
        return $command;
    }
    protected function createDeleteSqlCreator($method, $dbms){
        $sql = $this->annote->getSql($method, $dbms);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        $sql = $this->annote->getFile($method, $dbms);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        return new HermitAutoDeleteSqlCreator;
    }
    protected function createSelectCommand(PDO $pdo, ReflectionMethod $method){
        $dbms = HermitDatabaseMetaFactory::getDbms($pdo);
        $creator = $this->createSelectSqlCreator($method, $dbms);
        $creator->initialize($pdo, $method, $this->annote);
        if($creator instanceof HermitAppendableSqlCreator){
            $this->appendSql($method, $creator);
        }
        $valueType = HermitValueTypeFactory::create($this->annote, $method);
        $command = new HermitSelectCommand;
        $command->setContext($this->context);
        $command->setMethod($method);
        $command->setSqlCreator($creator);
        $command->setValueType($valueType);
        return $command;
    }
    protected function createSelectSqlCreator(ReflectionMethod $method, $dbms){
        $sql = $this->annote->getSql($method, $dbms);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        $sql = $this->annote->getFile($method, $dbms);
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
