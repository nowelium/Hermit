<?php

/**
 * @author nowelium
 */
class HermitSelectCommand extends AbstractHermitSqlCommand {
    public function execute(array $parameters){
        $statement = $this->buildStatement(HermitEvent::EVT_SELECT, $parameters);
        $statement->execute($parameters);
        $resultset = HermitResultSetFactory::create($this->method);
        return $resultset->execute($statement, $this->type);
    }
}
