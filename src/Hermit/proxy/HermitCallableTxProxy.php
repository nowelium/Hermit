<?php

/**
 * @author nowelium
 */
class HermitCallableTxProxy extends HermitCallableProxy {
    protected $context;
    public function __construct(HermitContext $ctx, HermitProxy $proxy, HermitTx $target, $methodName){
        parent::__construct($proxy, $target, $methodName);
        $this->context = $ctx;
    }
    public function request($name, array $parameters){
        $tx = $this->target;

        $type = HermitEvent::UNKNOWN;
        if(HermitNamingUtils::isProcedure($name)){
            $type = HermitEvent::EVT_PROCEDURE;
        } else if(HermitNamingUtils::isInsert($name)){
            $type = HermitEvent::EVT_INSERT;
        } else if(HermitNamingUtils::isUpdate($name)){
            $type = HermitEvent::EVT_UPDATE;
        } else if(HermitNamingUtils::isDelete($name)){
            $type = HermitEvent::EVT_DELETE;
        } else {
            $type = HermitEvent::EVT_SELECT;
        }
        $this->resume($tx, $name, $type);
        if(HermitEvent::isRead($type)){
            return $this->proxy->request($name, $parameters);
        }
        $callable = array($this->target, $this->methodName);
        return call_user_func($callable, $this->proxy, $name, $parameters);
    }
    protected function resume(HermitTx $tx, $methodName, $type){
        return $tx->resume($this->getDataSource($methodName, $type));
    }
    protected function getDataSource($methodName, $type){
        $target = $this->context->getName();
        return HermitDataSourceManager::get($target, $methodName, $type);
    }
}