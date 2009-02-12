<?php

/**
 * @author nowelium
 */
class HermitUpdateCommand extends AbstractHermitSqlCommand {
    public function execute(array $parameters){
        $statement = $this->buildStatement(HermitEvent::EVT_UPDATE, $parameters);
        $statement->execute($parameters);
        $resultset = new HermitUpdateQueryResultSet;
        return $resultset->execute($statement, $this->type);
    }
}
