<?php

/**
 * @author nowelium
 */
class HermitSimpleLogger extends HermitLogger {
    protected $appenders = array();
    public function addAppender(HermitLogAppender $appender){
        $this->appenders[] = $appender;
    }
    protected function doLog($level, array $args){
        $this->log($level, array_shift($args), $args);
    }
    protected function log($level, $format, array $params){
        if($level <= $this->level){
            return;
        }
        $c = count($this->appenders);
        if($c < 1){
            return;
        }
        
        $message = '[' . HermitLogLevel::toName($level) . ']' . vsprintf($format, $params);
        for($i = 0; $i < $c; ++$i){
            $this->appenders[$i]->append($message);
        }
    }
}