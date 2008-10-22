<?php

/**
 * @author nowelium
 */
abstract class HermitLoggerManager {
    protected static $logger;
    public static function setLogger(HermitLogger $logger){
        self::$logger = $logger;
    }
    /**
     * @return HermitLogger
     */
    public static function getLogger(){
        return self::$logger;
    }
    public static function init(){
        self::$logger = new HermitNopLogger;
    }
}

HermitLoggerManager::init();
