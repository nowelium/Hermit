<?php

/**
 * @author nowelium
 */
abstract class AbstractHermitTx implements HermitTx {
    private $txRule = array();
    private $connection;
    public function hasTransaction(){
    }
    public function begin(){
    }
    public function commit(){
    }
    public function rollback(){
    }
    public function suspend(){
    }
    public function resume(PDO $connection){
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
