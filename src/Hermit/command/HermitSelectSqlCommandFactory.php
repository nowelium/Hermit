<?php

/**
 * @author nowelium
 */
class HermitSelectSqlCommandFactory implements HermitCommandFactory {
    protected $annote;
    protected $method;
    public function setAnnote(HermitAnnote $annote){
        $this->annote = $annote;
    }
    public function setMethod(ReflectionMethod $method){
        $this->method = $method;
    }
    public function create(PDO $pdo, HermitContext $context){
        $creator = $this->createSelectSqlCreator($pdo);
        $creator->initialize($pdo, $this->method, $this->annote);
        if($creator instanceof HermitAppendableSqlCreator){
            $this->appendSql($creator);
        }
        $valueType = HermitValueTypeFactory::create($this->annote, $this->method);
        
        $command = new HermitSelectCommand;
        $command->setContext($context);
        $command->setMethod($this->method);
        $command->setSqlCreator($creator);
        $command->setValueType($valueType);
        return $command;
    }
    protected function createSelectSqlCreator(PDO $pdo){
        $dbms = HermitDatabaseMetaFactory::getDbms($pdo);
        $sql = $this->annote->getSql($this->method, $dbms);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        $sql = $this->annote->getFile($this->method, $dbms);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        return new HermitAutoSelectSqlCreator;
    }
    protected function appendSql(HermitAppendableSqlCreator $creator){
        $query = $this->annote->getQuery($this->method);
        if(null !== $query){
            $creator->addQuery($query);
        }
        $order = $this->annote->getOrder($this->method);
        if(null !== $order){
            $creator->addOrder($order);
        }
    }
}