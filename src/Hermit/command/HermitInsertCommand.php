<?php

/**
 * @author nowelium
 */
class HermitInsertCommand extends AbstractHermitSqlCommand {
    public function execute(array $parameters){
        $pdo = $this->getConnection(HermitEvent::EVT_INSERT);
        $builder = new HermitStatementBuilder($this->method, $this->sqlCreator);
        $stmt = $builder->build($pdo);
        $stmt->execute($parameters);
        $resultset = new HermitUpdateQueryResultSet;
        return $resultset->execute($stmt, $this->type);
    }
}
