<?php

/**
 * @author nowelium
 */
abstract class HermitLogger {
    protected $level;
    public function __construct(){
        $this->level = HermitLogLevel::OFF;
    }
    public function setLevel($level){
        $this->level = $level;
    }
    public function isFatalEnabled(){
        return HermitLogLevel::FATAL < $this->level;
    }
    public function fatal(){
        $this->doLog(HermitLogLevel::FATAL, func_get_args());
    }
    public function isErrorEnabled(){
        return HermitLogLevel::ERROR < $this->level;
    }
    public function error(){
        $this->doLog(HermitLogLevel::ERROR, func_get_args());
    }
    public function isWarnEnabled(){
        return HermitLogLevel::WARN < $this->level;
    }
    public function warn(){
        $this->doLog(HermitLogLevel::WARN, func_get_args());
    }
    public function isInfoEnabled(){
        return HermitLogLevel::INFO < $this->level;
    }
    public function info(){
        $this->doLog(HermitLogLevel::INFO, func_get_args());
    }
    public function isDebugEnabled(){
        return HermitLogLevel::DEBUG < $this->level;
    }
    public function debug(){
        $this->doLog(HermitLogLevel::DEBUG, func_get_args());
    }
    protected abstract function doLog($level, array $args);
}