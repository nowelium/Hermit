<?php

/**
 * @author nowelium
 */
class Hermit {
    protected $listeners = array();
    protected $delegators = array();
    public function __construct($class = null){
        if(is_null($class)){
            $e = new Exception;
            $trace = $e->getTrace();
            $class = HermitDaoManager::get($trace[1]['class']);
        } else if(HermitDaoManager::has($class)){
          $class = HermitDaoManager::get($class);
        }
        $this->proxy = self::__create($class);
    }
    public function __call($name, $parameters = array()){
        if(isset($this->delegators[$name])){
            $delegator = $this->delegators[$name];
            return self::__request($delegator, $name, $parameters);
        }
        if(isset($this->listeners[$name])){
            $response = self::__request($this->proxy, $name, $parameters);
            $listeners = $this->listeners[$name];
            foreach($listeners as $listener){
                self::__request($listener, $name, array($response));
            }
            return $response;
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
}

