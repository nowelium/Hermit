<?php

/**
 * @author nowelium
 */
class HermitProcedureCommand implements HermitSqlCommand {
    private $method;
    private $sqlCreator;
    private $type;
    public function __construct(ReflectionMethod $method, HermitSqlCreator $sqlCreator, HermitValueType $type){
        $this->method = $method;
        $this->sqlCreator = $sqlCreator;
        $this->type = $type;
    }

    public function execute(PDO $pdo, array $parameters){
        $sql = $this->sqlCreator->createSql($pdo);
        $stmt = HermitStatementBuilder::prepare($pdo, $this->method, $sql);
        $stmt->execute($parameters);
        $rs = new HermitProcedureResultSet;
        return $rs->execute($stmt, $this->type);
    }
}
