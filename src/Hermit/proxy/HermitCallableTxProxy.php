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
        $methodName = $this->methodName;
        if(HermitNamingUtils::isProcedure($methodName)){
        }

        $isWritable = false;
        $type = HermitEvent::UNKNOWN;
        if(HermitNamingUtils::isProcedure($methodName)){
            $type = HermitEvent::EVT_PROCEDURE;
        } else if(HermitNamingUtils::isInsert($methodName)){
            $type = HermitEvent::EVT_INSERT;
        } else if(HermitNamingUtils::isUpdate($methodName)){
            $type = HermitEvent::EVT_UPDATE;
        } else if(HermitNamingUtils::isDelete($methodName)){
            $type = HermitEvent::EVT_DELETE;
        } else {
            $type = HermitEvent::EVT_SELECT;
        }
        $this->resume($tx, $methodName, $type);
        if(HermitEvent::isRead($type)){
            $callable = array($this->proxy, $this->methodName);
            return call_user_func($callable, $name, $parameters);
        }
        $callable = array($this->target, $this->methodName);
        return call_user_func($callable, $this->proxy, $name, $parameters);
    }
    protected function resume(HermitTx $tx, $methodName, $type){
        return $tx->resume($this->getDataSource($methodName, $type));
    }
    protected function getDataSource($methodName, $type){
        $targetClass = $this->context->getTargetClass();
        return HermitDataSourceManager::get($targetClass, $methodName, $type);
    }
}