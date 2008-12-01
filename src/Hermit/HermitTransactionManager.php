<?php

/**
 * @author nowelium
 */
class HermitTransactionManager implements HermitBehaviorWrapper {
    protected static $instance;
    protected $transactionScripts = array();
    protected $proxyClass = 'HermitCallableTxProxy';
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
        return $instance->getTransactionScript($targetClass);
    }
    public static function setProxyClass($className){
        $instance = self::getInstance();
        $instance->proxyClass = $className;
    }
    public function has($targetClass){
        $instance = self::getInstance();
        return isset($instance->transactionScripts[$targetClass]);
    }
    protected function getTransactionScript($targetClass){
        if(isset($this->transactionScripts[$targetClass])){
            return $this->transactionScripts[$targetClass];
        }
        return null;
    }
    public function createProxy(HermitContext $ctx, HermitProxy $proxy){
        $instance = self::getInstance();
        $className = $instance->proxyClass;
        $targetClass = $ctx->getTargetClass();
        $tx = $instance->getTransactionScript($targetClass);
        return new $className($ctx, $proxy, $tx, 'proceed');
    }
}
