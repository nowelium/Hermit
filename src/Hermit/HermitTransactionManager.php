<?php

/**
 * @author nowelium
 */
class HermitTransactionManager implements HermitBehaviorWrapper {
    private static $instance;
    private $transactionScripts = array();
    private function __construct(){
        // nop
    }
    protected static function getInstance(){
        if(null === self::$instance){
            self::$instance = new self;
        }
        if(!HermitRegister::hasBehavior(__CLASS__)){
            HermitRegister::putBehavior(__CLASS__, self::$instance);
        }
        return self::$instance;
    }
    public static function set($targetClass, HermitTx $tx){
        $instance = self::getInstance();
        $instance->transactionScripts[$targetClass] = $tx;
    }
    public static function get($targetClass){
        $instance = self::getInstance();
        if(isset($instance->transactionScripts[$targetClass])){
            return null;
        }
        return $instance->transactionScripts[$targetClass];
    }
    public function has($targetClass){
        $instance = self::getInstance();
        return isset($instance->transactionScripts[$targetClass]);
    }
    public function createProxy(HermitProxy $proxy, $targetClass){
        $tx = self::get($targetClass);
        $tx->resume(HermitDataSourceManager::get($targetClass));
        return new HermitCallableProxy($proxy, array($tx, 'proceed'));
    }
}
