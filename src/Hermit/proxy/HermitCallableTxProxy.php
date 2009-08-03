<?php

/**
 * @author nowelium
 */
class HermitCallableTxProxy implements HermitProxy {
    protected $context;
    protected $proxy;
    protected $txManager;
    protected $methodName;
    public function __construct(HermitContext $ctx, HermitProxy $proxy, HermitTransactionManager $txManager, $methodName){
        $this->context = $ctx;
        $this->proxy = $proxy;
        $this->txManager = $txManager;
        $this->methodName = $methodName;
    }
    public function request($name, array $parameters){
        $type = self::getEventType($name);
        $tx = $this->txManager->get($this->context->getName());
        $this->resume($tx, $name, $type);
        return call_user_func(array($tx, $this->methodName), $this->proxy, $name, $parameters);
    }
    protected function resume(HermitTx $tx, $methodName, $type){
        return $tx->resume($this->getDataSource($methodName, $type));
    }
    protected function getDataSource($methodName, $type){
        $target = $this->context->getName();
        return HermitDataSourceManager::get($target, $methodName, $type);
    }
    protected static function getEventType($methodName){
        if(HermitNamingUtils::isProcedure($methodName)){
            return HermitEvent::EVT_PROCEDURE;
        }
        if(HermitNamingUtils::isInsert($methodName)){
            return HermitEvent::EVT_INSERT;
        }
        if(HermitNamingUtils::isUpdate($methodName)){
            return HermitEvent::EVT_UPDATE;
        }
        if(HermitNamingUtils::isDelete($methodName)){
            return HermitEvent::EVT_DELETE;
        }
        return HermitEvent::EVT_SELECT;
    }
}