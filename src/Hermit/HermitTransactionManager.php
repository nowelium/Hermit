<?php

/**
 * @author nowelium
 */
class HermitTransactionManager implements HermitBehaviorWrapper {
    protected static $instance;
    protected $transactionScripts = array();
    protected $transactionProxy = array();
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
        $instance->transactionProxy[$targetClass] = $instance->proxyClass;
    }
    public static function get($targetClass){
        $instance = self::getInstance();
        return $instance->getTransactionScript($targetClass);
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
        $targetClass = $ctx->getName();
        $proxyClass = $instance->transactionProxy[$targetClass];
        return new $proxyClass($ctx, $proxy, $instance, 'proceed');
    }
}
