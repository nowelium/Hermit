<?php

/**
 * @author nowelium
 */
class HermitUpdateCommand extends AbstractHermitSqlCommand {
    public function execute(array $parameters){
        $statement = $this->buildStatement(HermitEvent::EVT_UPDATE, $parameters);
        $this->statement->execute($parameters);
        $resultset = new HermitUpdateQueryResultSet;
        return $resultset->execute($this->statement, $this->type);
    }
}
