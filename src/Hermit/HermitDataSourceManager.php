<?php

/**
 * @author nowelium
 */
abstract class HermitDataSourceManager {
    private static $default;
    private static $datasources = array();
    private function __construct(){
    }
    public static function setDefault(PDO $default){
        self::$default = $default;
    }
    public static function set($targetClass, PDO $pdo){
        self::$datasources[$targetClass] = $pdo;
    }
    public static function get($targetClass){
        if(!self::has($targetClass)){
            return self::$default;
        }
        return self::$datasources[$targetClass];
    }
    public static function has($targetClass){
        return isset(self::$datasources[$targetClass]);
    }
}
