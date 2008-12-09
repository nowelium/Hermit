<?php

/**
 * @author nowelium
 */
class HermitSqlParameterSequence extends HermitSqlParameterHash {
    public function replace($key, $name, $defaultValue){
        $inputParams = $this->getInputParameters();
        $index = $this->names[$name];
        return join(',', array_fill(0, count($inputParams[$index]), '?'));
    }
    public function bind(PDOStatement $stmt, $value){
        $logger = HermitLoggerManager::getLogger();
        if($logger->isDebugEnabled()){
            $buf = '';
            foreach($this->names as $name => $pos){
                $buf .= $pos . ' => ' . join(',', $value[$pos]);
            }
            $logger->debug('{%s} statement binds parameter {:index => param} = %s', __CLASS__, $buf);
        }
        $index = 0;
        foreach($this->names as $name => $pos){
            foreach($value[$pos] as $v){
                $stmt->bindValue($pos + (++$index), $v);
            }
        }
    }
}
