<?php

/**
 * @author nowelium
 */
class HermitSelectCommand extends AbstractHermitSqlCommand {
    public function execute(array $parameters){
        $pdo = $this->getConnection(HermitEvent::EVT_SELECT);
        $builder = new HermitStatementBuilder($this->method, $this->sqlCreator);
        $stmt = $builder->build($pdo);
        $stmt->execute($parameters);
        $resultset = HermitResultSetFactory::create($this->method);
        return $resultset->execute($stmt, $this->type);
    }
}
