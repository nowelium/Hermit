<?php

/**
 * @author nowelium
 */
class Hermit {
    protected $listeners = array();
    protected $delegaters = array();
    public function __construct($class = null){
        if(is_null($class)){
            $e = new Exception;
            $trace = $e->getTrace();
            $class = HermitDaoManager::get($trace[1]['class']);
        }
        $this->proxy = self::__create($class);
    }
    public function __call($name, $parameters = array()){
        if(isset($this->delegater[$name])){
            return self::__request($this->delegater[$name], $name, $parameters);
        }
        return self::__request($this->proxy, $name, $parameters);
    }
    protected static function __request(HermitProxy $hermit, $name, array $params){
        return $hermit->request($name, $params);
    }
    protected static function __create($targetClass){
        if(is_object($targetClass)){
            return HermitObjectProxy::delegate(new ReflectionObject($targetClas), $targetClass);
        }
        $reflector = new ReflectionClass($targetClass);
        if($reflector->isInterface()){
            return HermitInterfaceProxy::delegate($reflector);
        }
        return HermitClassProxy::delegate($reflector);
    }
}

