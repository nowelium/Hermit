<?php

/**
 * @author nowelium
 */
class Hermit {
    protected $listeners = array();
    protected $delegaters = array();
    public function __construct($class){
        $this->proxy = self::__create($class);
    }
    public function __call($name, $parameters = array()){
        if(isset($this->delegater[$name])){
            return self::__request($this->delegater[$name], $name, $parameters);
        }
        return self::__request($this->proxy, $name, $parameters);
    }
    protected static function __request(Hermit $hermit, $name, array $params){
        return $hermit->request($name, $params);
    }
    protected static function __create($class){
        if(is_object($class)){
            return HermitObjectProxy::delegate(new ReflectionObject($class), $class);
        }
        if(isset(Hermit::$classMap[$class])){
            return self::__createProxy(Hermit::$classMap[$class]);
        }
        if(class_exists($class)){
            return self::__createProxy($class);
        }
        throw new RuntimeException('Hermit does not create: ' . $class);
    }
    protected static function __createProxy($targetClass){
        $reflector = new ReflectionClass($targetClass);
        if($reflector->isInterface()){
            return HermitInterfaceProxy::delegate($reflector);
        }
        return HermitClassProxy::delegate($reflector);
    }
}

