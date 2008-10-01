<?php

/**
 * @author nowelium
 */
abstract class AbstractHermitTx implements HermitTx {
    private $txRule = array();
    private $connection;
    private $begin = false;
    public final function hasTransaction(){
        if($this->begin){
            return true;
        }
        return $this->begin = $this->connection->beginTransaction();
    }
    public final function begin(){
        try {
            if(!$this->begin){
                $this->begin = $this->connection->beginTransaction();
            }
        } catch(Exception $e){
            // TODO: Nest transaction
            throw $e;
        }
    }
    public final function commit(){
        try {
            if($this->connection->commit()){
                $this->begin = false;
            }
        } catch(Exception $e){
            throw $e;
        }    
    }
    public final function rollback(){
        try {
            if ($this->hasTransaction()) {
                return $this->connection->rollback();
            }
        } catch(Exception $e){
            throw $e;
        }
    }
    public final function suspend(){
        return $this->connection;
    }
    public final function resume(PDO $connection){
        $this->connection = $connection;
    }
    public final function complete(Exception $e){
        foreach($this->txRule as $rule){
            try {
                if($rule->isAssignableFrom($e)){
                    return $rule->complete();
                }
            } catch(Exception $e1){
                throw $e1;
            }
        }
        $this->rollback();
        return false;
    }
    public final function addCommitRule(Exception $e){
        $this->txRule[] = new HermitTxRule($this, $e, true);
    }
    public final function addRollbackRurle(Exception $e){
        $this->txRule[] = new HermitTxRule($this, $e, false);
    }
}
