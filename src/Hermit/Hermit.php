<?php

/**
 * @author nowelium
 */
class Hermit {
    protected $proxy;
    protected $calls = array();
    protected static $behaviors = array();
    public function __construct($class = null){
        if(is_null($class)){
            $e = new Exception;
            $trace = $e->getTrace();
            $class = HermitDaoManager::get($trace[1]['class']);
        } else if(HermitDaoManager::has($class)){
          $class = HermitDaoManager::get($class);
        }
        $proxy = self::__create($class);
        $this->proxy = self::wrap($proxy, $class);
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
    protected static function __create($targetClass){
        if(is_object($targetClass)){
            return HermitObjectProxy::delegate(new ReflectionObject($targetClass), $targetClass);
        }
        $reflector = new ReflectionClass($targetClass);
        if($reflector->isInterface()){
            return HermitInterfaceProxy::delegate($reflector);
        }
        return HermitClassProxy::delegate($reflector);
    }
    protected static function wrap(HermitProxy $proxy, $targetClass){
        if(0 < count(self::$behaviors)){
            foreach(self::$behaviors as $behavior){
                if($behavior->has($targetClass)){
                    return $behavior->createProxy($proxy, $targetClass);
                }
            }
        }
        return $proxy;
    }
}
