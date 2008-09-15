<?php

/**
 * @author nowelium
 */
class HermitTxRurle {
    private $tx;
    private $exception;
    private $commit;
    private $reflector;
    public function __construct(HermitTx $tx, Exception $e, $commit){
        $this->tx = $tx;
        $this->exception = $e;
        $this->commit = $commit;
        $this->reflector = new ReflectionObject($e);
    }
    public function isAssignableFrom(Exception $e){
        return $this->reflector->isSubclassOf($e);
    }
    public function complete(){
        if($this->commit){
            $this->tx->commit();
        } else {
            $this->tx->rollback();
        }
        return $this->commit;
    }
}
