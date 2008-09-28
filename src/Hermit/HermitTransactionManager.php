<?php

/**
 * @author nowelium
 */
abstract class HermitTransactionManager {
    private static $transactionScripts = array();
    private function __construct(){
        // nop
    }
    public static function set($targetClass, HermitTx $tx){
    }
    public static function get($targetClass){
    }
    public static function has($targetClass){
    }
    public static function createProxy(HermitProxy $proxy, $targetClass){
        $tx = self::get($targetClass);
        return new HermitCallableProxy($proxy, array($tx, 'proceed'));
    }
}
