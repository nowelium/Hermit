<?php

/**
 * @author nowelium
 */
class HermitTxException extends RuntimeException {
    public function __construct($message = null, $code = 123){
        if(is_string($code)){
            $this->codeString = $code;
            $code = 123;
        }
        parent::__construct($message, $code);
    }
    public function __toString(){
        return var_export($this, true);
    }
}