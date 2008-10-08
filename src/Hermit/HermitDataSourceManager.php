<?php

/**
 * @author nowelium
 */
abstract class HermitDataSourceManager {
    private static $default;
    private static $callback;
    private static $datasources = array();
    private function __construct(){
        // mp@
    }
    public static function setDefault(PDO $default){
        self::$default = $default;
    }
    public static function hasDefault(){
        return null !== self::$default;
    }
    public static function setCallback(array $callback){
        self::$callback = $callback;
    }
    public static function hasCallback(){
        return null !== self::$callback;
    }
    public static function set($targetClass, PDO $pdo){
        self::$datasources[$targetClass] = $pdo;
    }
    public static function get($targetClass, $method = null, $type = HermitEvent::UNKNOWN){
        if(self::hasCallback()){
            return call_user_func_array(self::$callback, array($targetClass, $method, $type));
        }

        if(!self::has($targetClass)){
            return self::$default;
        }
        return self::$datasources[$targetClass];
    }
    public static function has($targetClass){
        return isset(self::$datasources[$targetClass]);
    }
}
