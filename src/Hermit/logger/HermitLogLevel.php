<?php

/**
 * @author nowelium
 */
class HermitLogLevel {
    const OFF = -1;
    const FATAL = 0;
    const ERROR = 4;
    const WARN = 6;
    const INFO = 10;
    const DEBUG = 20;
    const ALL = 30;
    
    const OFF_NAME = 'OFF';
    const FATAL_NAME = 'FATAL';
    const ERROR_NAME = 'ERROR';
    const WARN_NAME = 'WARN';
    const INFO_NAME = 'INFO';
    const DEBUG_NAME = 'DEBUG';
    const ALL_NAME = 'ALL';
    
    const UNKNOWN = 'unknown level';
    
    public function toName($level){
        if(self::ALL === $level){
            return self::ALL_NAME;
        }
        if(self::DEBUG === $level){
            return self::DEBUG_NAME;
        }
        if(self::INFO === $level){
            return self::INFO_NAME;
        }
        if(self::WARN === $level){
            return self::WARN_NAME;
        }
        if(self::ERROR === $level){
            return self::ERROR_NAME;
        }
        if(self::FATAL === $level){
            return self::FATAL_NAME;
        }
        if(self::OFF === $level){
            return self::OFF_NAME;
        }
        return self::UNKNOWN;
    }
}