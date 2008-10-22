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
        return HermitLogLevel::FATAL <= $this->level;
    }
    public function fatal(){
        $args = func_get_args();
        $this->doLog(HermitLogLevel::FATAL, $args);
    }
    public function isErrorEnabled(){
        return HermitLogLevel::ERROR <= $this->level;
    }
    public function error(){
        $args = func_get_args();
        $this->doLog(HermitLogLevel::ERROR, $args);
    }
    public function isWarnEnabled(){
        return HermitLogLevel::WARN <= $this->level;
    }
    public function warn(){
        $args = func_get_args();
        $this->doLog(HermitLogLevel::WARN, $args);
    }
    public function isInfoEnabled(){
        return HermitLogLevel::INFO <= $this->level;
    }
    public function info(){
        $args = func_get_args();
        $this->doLog(HermitLogLevel::INFO, $args);
    }
    public function isDebugEnabled(){
        return HermitLogLevel::DEBUG <= $this->level;
    }
    public function debug(){
        $args = func_get_args();
        $this->doLog(HermitLogLevel::DEBUG, $args);
    }
    protected abstract function doLog($level, array $args);
}