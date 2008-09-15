<?php

/**
 * @author nowelium
 */
class HermitSelectCommand implements HermitCommand {
    private $method;
    private $sqlCreator;
    public function __construct(ReflectionMethod $method, HermitSqlCreator $sqlCreator){
        $this->method = $method;
        $this->sqlCreator = $sqlCreator;
    }
    public function execute(PDO $pdo, array $parameters){
        $sql = $this->sqlCreator->createSql();
        $stmt = HermitStatementBuilder::prepare($pdo, $this->method, $sql);
        $stmt->execute($parameters);
        return HermitResultSet::create($stmt, $this->method);
    }
}
