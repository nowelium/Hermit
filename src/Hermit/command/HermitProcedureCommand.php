<?php

/**
 * @author nowelium
 */
class HermitProcedureCommand extends AbstractHermitSqlCommand {
    protected $annote;
    public function setAnnote(HermitAnnote $annote){
        $this->annote = $annote;
    }
    public function execute(array $parameters){
        $targetClass = $this->context->getTargetClass();
        $pdo = $this->getConnection(HermitEvent::EVT_PROCEDURE);
        self::setupProcedureConnection($pdo);

        if($this->sqlCreator instanceof HermiSetupSqlCreator){
            if($this->sqlCreator->hasSetupSql()){
                $setupBuilder = new HermitSetupStatementBuilder($targetClass, $this->method, $this->annote, $this->sqlCreator);
                $setupStatement = $setupBuilder->build($pdo, $parameters);
                $setupStatement->execute($parameters);
                $setupStatement->closeCursor();
            }
        }

        $builder = new HermitProcedureStatementBuilder($targetClass, $this->method, $this->annote, $this->sqlCreator);
        $stmt = $builder->build($pdo, $parameters);
        $stmt->execute($parameters);

        $rs = HermitProcedureResultSetFactory::create($pdo, $this->method, $this->annote, $stmt->getSqlParameter());
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
