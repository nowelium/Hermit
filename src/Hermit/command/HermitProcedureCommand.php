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
        $builder = new HermitProcedureStatementBuilder($this->method, $this->annote, $this->sqlCreator);
        $stmt = $builder->build($pdo);
        $stmt->execute($parameters);
        $rs = HermitProcedureResultSetFactory::create($pdo, $stmt->getSqlParameter());
        if($rs instanceof HermitParameterBind){
            $rs->bindParameter($pdo, $parameters);
        }
        return $rs->execute($stmt, $this->type);
    }
}
