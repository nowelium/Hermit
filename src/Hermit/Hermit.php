<?php

/**
 * @author nowelium
 */
class Hermit {
    protected $proxy;
    protected $context;
    protected static $behaviors = array();
    public function __construct($class = null){
        if(is_null($class)){
            $e = new Exception;
            $trace = $e->getTrace();
            $class = HermitDaoManager::get($trace[1]['class']);
        } else if(HermitDaoManager::has($class)){
          $class = HermitDaoManager::get($class);
        }
        $this->proxy = self::__create($this, $class);
    }
    public function __call($name, $parameters = array()){
        if(0 < count($this->calls)){
            foreach($this->calls as $call){
                if($call->has($name)){
                    return $call->execute($this->proxy, $name, $parameters);
                }
            }
        }
        return self::__request($this->proxy, $name, $parameters);
    }
    protected static function __request(HermitProxy $proxy, $name, array $params){
        return $proxy->request($name, $params);
    }
    protected static function __create(self $instance, $targetClass){
        $proxy = null;
        $reflector = null;
        $ctx = null;
        if(is_object($targetClass)){
            $reflector = new ReflectionObject($targetClass);
            $ctx = new HermitContext($reflector);
            $proxy = HermitObjectProxy::delegate($ctx, $reflector, $targetClass);
        } else {
            $reflector = new ReflectionClass($targetClass);
            $ctx = new HermitContext($reflector);
            if($reflector->isInterface()){
                $proxy = HermitInterfaceProxy::delegate($ctx, $reflector);
            } else {
               $proxy = HermitClassProxy::delegate($ctx, $reflector);
            }
        }
        $instance->context = $ctx;
        return self::wrap($ctx, $proxy);
    }
    protected static function wrap(HermitContext $ctx, HermitProxy $proxy){
        if(0 < count(self::$behaviors)){
            $targetClass = $ctx->getName();
            foreach(self::$behaviors as $behavior){
                if($behavior->has($targetClass)){
                    return $behavior->createProxy($ctx, $proxy);
                }
            }
        }
        return $proxy;
    }
}
