<?php

/**
 * @auhtor nowelium
 */
abstract class HermitDaoManager {
    private static $daoClasses = array();
    private function __construct(){
        // nop
    }
    public static function set($targetClass, $assignedClass){
        self::$daoClasses[$targetClass] = $assignedClass;
    }
    public static function get($targetClass){
        if(isset(self::$daoClasses[$targetClass])){
            return self::$daoClasses[$targetClass];
        }
        return null;
    }
    public static function has($targetClass){
        return isset(self::$daoClasses[$targetClass]);
    }
}
