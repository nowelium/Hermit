<?php

abstract class HermitTransactionManager {
    private static $transactionScripts = array();
    private function __construct(){
        // nop
    }
    public static function set($targetClass, HermitTx $tx){
        self::$transactionScripts[$targetClass] = $tx;
    }
    public static function get($targetClass){
        return self::$transactionScripts[$targetClass];
    }
    public static function has($targetClass){
        return isset(self::$transactionScripts[$targetClass]);
    }
}
