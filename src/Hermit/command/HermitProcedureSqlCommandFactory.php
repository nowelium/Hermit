<?php

/**
 * @author nowelium
 */
class HermitProcedureSqlCommandFactory implements HermitCommandFactory {
    protected $annote;
    protected $method;
    public function setAnnote(HermitAnnote $annote){
        $this->annote = $annote;
    }
    public function setMethod(ReflectionMethod $method){
        $this->method = $method;
    }
    public function create(PDO $pdo, HermitContext $context){
        $creator = $this->createProcedureSqlCreator($pdo);
        $creator->initialize($pdo, $this->method, $this->annote);
        $valueType = HermitValueTypeFactory::create($this->annote, $this->method);

        $batchMode = $this->annote->getBatchMode($this->method);
        $context->setBatchMode($batchMode);
        
        $command = new HermitProcedureCommand;
        $command->setContext($context);
        $command->setMethod($this->method);
        $command->setSqlCreator($creator);
        $command->setValueType($valueType);
        $command->setAnnote($this->annote);
        return $command;
    }
    protected function createProcedureSqlCreator(PDO $pdo){
        $dbms = HermitDatabaseMetaFactory::getDbms($pdo);
        $sql = $this->annote->getProcedure($this->method);
        if(null !== $sql){
            return new HermitProcedureCallSqlCreator($sql);
        }
        $sql = $this->annote->getSql($this->method, $dbms);
        if(null !== $sql){
            return new HermitProcedureCallSqlCreator($sql);
        }
        $sql = $this->annote->getFile($this->method);
        if(null !== $sql){
            return new HermitProcedureCallSqlCreator($sql);
        }
        throw new BadMethodCallException('method: "' . $this->method->getName() . '" was not apply to Procedure command');
    }
}