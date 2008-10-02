<?php

/**
 * @author nowelium
 */
class HermitProcedureCommand implements HermitSqlCommand {
    private $method;
    private $sqlCreator;
    private $type;
    private $annote;
    public function setMethod(ReflectionMethod $method){
        $this->method = $method;
    }
    public function setSqlCreator(HermitSqlCreator $sqlCreator){
        $this->sqlCreator = $sqlCreator;
    }
    public function setValueType(HermitValueType $type){
        $this->type = $type;
    }
    public function setAnnote(HermitAnnote $annote){
        $this->annote = $annote;
    }
    public function execute(PDO $pdo, array $parameters){
        if($this->sqlCreator instanceof HermiSetupSqlCreator){
            if($this->sqlCreator->hasSetupSql()){
                $setupBuilder = new HermitSetupStatementBuilder($this->method, $this->annote, $this->sqlCreator);
                $setupStatement = $setupBuilder->build($pdo);
                $setupStatement->execute($parameters);
            }
        }
        
        $builder = new HermitProcedureStatementBuilder($this->method, $this->annote, $this->sqlCreator);
        $stmt = $builder->build($pdo);
        $stmt->execute($parameters);
        $rs = HermitProcedureResultSetFactory::create($pdo, $stmt->getSqlParameter());
//
//        to pdo_mysql cause: Fatal error: Uncaught exception 'PDOException' with message 'SQLSTATE[HY000]: General error: 2014 Cannot execute queries while other unbuffered queries are active.
//        if($rs instanceof HermitParameterBind){
//            $rs->bindParameter($pdo, $parameters);
//        }
//        return $rs->execute($stmt, $this->type);
//
        $returnValue = $rs->execute($stmt, $this->type);
        if($rs instanceof HermitParameterBind){
            $rs->bindParameter($pdo, $parameters);
        }
        return $returnValue;
    }
}
