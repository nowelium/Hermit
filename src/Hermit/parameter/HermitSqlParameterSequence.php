<?php

/**
 * @author nowelium
 */
class HermitSqlParameterSequence extends HermitSqlParameterHash {
    public function replace($key, $name, $defaultValue){
        return $this->bindKeys[] = '?';
    }
    public function bind(PDOStatement $stmt, $value){
        $logger = HermitLoggerManager::getLogger();
        if($logger->isDebugEnabled()){
            $buf = '';
            foreach($this->names as $name => $pos){
                $buf .= $pos . ' => ' . $value[$pos];
            }
            $logger->debug('statement binds parameter {:index => param} = %s', $buf);
        }
        foreach($this->names as $name => $pos){
            $stmt->bindValue($pos, $value[$pos]);
        }
    }
}
