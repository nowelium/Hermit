<?php

/**
 * @author nowelium
 */
class HermitInsertCommand extends AbstractHermitSqlCommand {
    public function execute(array $parameters){
        $pdo = $this->getConnection(HermitEvent::EVT_INSERT);
        $genStmt = false;
        if(null === $this->statement){
            $genStmt = true;
        } else {
            if(!$this->context->isBatchMode()){
                $genStmt = true;
            }
        }
        if($genStmt){
            $builder = new HermitStatementBuilder($this->context->getTargetClass(), $this->method, $this->sqlCreator);
            $this->statement = $builder->build($pdo, $parameters);
        }
        $this->statement->execute($parameters);
        
        $resultset = new HermitUpdateQueryResultSet;
        return $resultset->execute($this->statement, $this->type);
    }
}
