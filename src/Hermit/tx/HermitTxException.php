<?php

/**
 * @author yusuke.hata
 */
class HermitTxException extends RuntimeException {
    public function __construct($message, $code = -1){
        parent::__construct($message, $code);
    }
}