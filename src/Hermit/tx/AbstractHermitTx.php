<?php

/**
 * @author nowelium
 */
abstract class AbstractHermitTx implements HermitTx {
    private $txRule = array();
    public function complete(Exception $e){
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
    public function addCommitRule(Exception $e){
        $this->txRule[] = new HermitTxRule($this, $e, true);
    }
    public function addRollbackRurle(Exception $e){
        $this->txRule[] = new HermitTxRule($this, $e, false);
    }
}
