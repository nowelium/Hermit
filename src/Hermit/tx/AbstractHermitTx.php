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
        try {
            return $this->begin = $this->connection->beginTransaction();
        } catch(Exception $e){
            //
            // FIXME: PDO already transaction
            // pdo_dbh.c:602
            // if (dbh->in_txn) {
            //   zend_throw_exception_ex(php_pdo_get_exception(), 0 TSRMLS_CC, "There is already an active transaction");
            //
            //
            if(0 === strcasecmp('There is already an active transaction', $e->getMessage())){
                return $this->begin = true;
            }
            throw $e;
        }
    }
    public final function begin(){
        try {
            if(!$this->begin){
                $this->begin = $this->connection->beginTransaction();
            }
        } catch(Exception $e){
            // TODO: Nest transaction
            throw new HermitTxException($e->getMessage());
        }
    }
    public final function commit(){
        try {
            if($this->connection->commit()){
                $this->begin = false;
            }
        } catch(Exception $e){
            throw new HermitTxException($e->getMessage(), $e->getCode());
        }
    }
    public final function rollback(){
        try {
            if ($this->hasTransaction() && $this->connection->rollback()){
                $this->begin = false;
            }
        } catch(Exception $e){
            throw new HermitTxException($e->getMessage(), $e->getCode());
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
                throw new HermitTxException($e1->getMessage(), $e1->getCode());
            }
        }
        $this->rollback();
        return false;
    }
    public final function addCommitRule(Exception $e){
        $this->txRule[] = new HermitTxRule($this, $e, true);
    }
    public final function addRollbackRule(Exception $e){
        $this->txRule[] = new HermitTxRule($this, $e, false);
    }
}
