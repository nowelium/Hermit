<?php

/**
 * @author nowelium
 */
abstract class AbstractHermitUpdateSqlCommandFactory implements HermitCommandFactory {
    protected $annote;
    protected $method;
    public function setAnnote(HermitAnnote $annote){
        $this->annote = $annote;
    }
    public function setMethod(ReflectionMethod $method){
        $this->method = $method;
    }
    protected function getStaticSqlCreator(PDO $pdo){
        $dbms = HermitDatabaseMetaFactory::getDbms($pdo);
        $sql = $this->annote->getSql($this->method, $dbms);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        $sql = $this->annote->getFile($this->method, $dbms);
        if(null !== $sql){
            return new HermitStaticSqlCreator($sql);
        }
        return null;
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