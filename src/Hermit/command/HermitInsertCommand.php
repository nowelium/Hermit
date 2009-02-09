<?php

/**
 * @author nowelium
 */
class HermitInsertCommand extends AbstractHermitSqlCommand {
    public function execute(array $parameters){
        $statement = $this->buildStatement(HermitEvent::EVT_INSERT, $parameters);
        $statement->execute($parameters);
        $resultset = new HermitUpdateQueryResultSet;
        return $resultset->execute($statement, $this->type);
    }
}
