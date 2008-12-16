<?php

/**
 * @author nowelium
 */
abstract class HermitAutoloader {
    private static $classPath = array(
        '/',
        '/annote',
        '/parameter',
        '/proxy',
        '/meta',
        '/creator',
        '/command',
        '/statement',
        '/resultset',
        '/responder',
        '/tx',
        '/util',
        '/valuetype',
        '/logger'
    );
    private static $userClassPath = array();
    private static $unmatch = array();
    private static $current;
    public static function initialize(){
        self::$current = dirname(__FILE__);
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }
    public static function import($dir){
        self::$userClassPath[] = $dir;
    }
    public static function autoload($className){
        if(self::classload(self::$classPath, $className, self::$current)){
            return true;
        }
        if(self::classload(self::$userClassPath, $className)){
            return true;
        }
        return false;
    }
    protected static function classload(array $pathes, $className, $prefix = ''){
        $file = $className . '.php';
        foreach($pathes as $path){
            $filePath = $prefix . $path . DIRECTORY_SEPARATOR . $file;
            if(isset(self::$unmatch[$filePath]) && self::$unmatch[$filePath]){
                continue;
            }
            if(file_exists($filePath)){
                require $filePath;
                return true;
            }
            self::$unmatch[$filePath] = true;
        }
        return false;
    }
}

HermitAutoloader::initialize();
