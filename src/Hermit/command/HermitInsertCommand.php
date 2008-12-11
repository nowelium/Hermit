<?php

/**
 * @author nowelium
 */
class HermitInsertCommand extends AbstractHermitSqlCommand {
    public function execute(array $parameters){
        $pdo = $this->getConnection(HermitEvent::EVT_INSERT);
        $builder = new HermitStatementBuilder($this->context->getTargetClass(), $this->method, $this->sqlCreator);
        $stmt = $builder->build($pdo, $parameters);
        $stmt->execute($parameters);
        $resultset = new HermitUpdateQueryResultSet;
        return $resultset->execute($stmt, $this->type);
    }
}
