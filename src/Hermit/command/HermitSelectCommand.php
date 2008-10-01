<?php

/**
 * @author nowelium
 */
class HermitSelectCommand implements HermitSqlCommand {
    private $method;
    private $sqlCreator;
    private $type;
    public function __construct(ReflectionMethod $method, HermitSqlCreator $sqlCreator, HermitValueType $type){
        $this->method = $method;
        $this->sqlCreator = $sqlCreator;
        $this->type = $type;
    }
    public function execute(PDO $pdo, array $parameters){
        $builder = new HermitStatementBuilder($this->method, $this->sqlCreator);
        $stmt = $builder->build($pdo);
        $stmt->execute($parameters);
        $resultset = HermitResultSetFactory::create($this->method);
        return $resultset->execute($stmt, $this->type);
    }
}
