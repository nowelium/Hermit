<?php

/**
 * @author nowelium
 */
class HermitDeleteCommand extends AbstractHermitSqlCommand {
    public function execute(array $parameters){
        $statement = $this->buildStatement(HermitEvent::EVT_DELETE, $parameters);
        $statement->execute($parameters);
        $resultset = new HermitUpdateQueryResultSet;
        return $resultset->execute($statement, $this->type);
    }
}
