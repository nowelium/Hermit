<?php

/**
 * @author nowelium
 */
class HermitSelectCommand extends AbstractHermitSqlCommand {
    public function execute(array $parameters){
        $pdo = $this->getConnection(HermitEvent::EVT_SELECT);
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
        
        $resultset = HermitResultSetFactory::create($this->method);
        return $resultset->execute($this->statement, $this->type);
    }
}
