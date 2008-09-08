<?php

/**
 * @author nowelium
 */
abstract class Hermit {
    public static $classMap = array();
    public static function bind($targetClass, $dao){
        self::$classMap[$targetClass] = $dao;
    }
    public static function create(PDO $pdo, $class = null){
        if(is_null($class)){
            $e = new Exception;
            $trace = $e->getTrace();
            return Hermit::create($pdo, $trace[1]['class']);
        }
        if(is_object($class)){
            return HermitClassProxy::delegate($pdo, new ReflectionObject($class), $class);
        }
        if(isset(Hermit::$classMap[$class])){
            return self::createProxy($pdo, Hermit::$classMap[$class]);
        }
        if(class_exists($class)){
            return self::create($pdo, $class);
        }
        throw new InvalidArgumentException('nosuch class: ' . $class);
    }
    protected static function createProxy(PDO $pdo, $targetClass){
        $reflector = new ReflectionClass($targetClass);
        if($reflector->isInterface()){
            return HermitInterfaceProxy::delegate($pdo, $reflector);
        }
        return HermitFutureProxy::delegate($pdo, $reflector, $targetClass);
    }
}
