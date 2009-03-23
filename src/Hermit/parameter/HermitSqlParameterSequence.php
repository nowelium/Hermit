<?php

/**
 * @author nowelium
 */
class HermitSqlParameterSequence extends HermitSqlParameterHash {
    const key_prefix = ':seq_';
    public function replace($key, $name, $defaultValue){
        $inputParams = $this->getInputParameters();
        $index = $this->names[$name];
        $bindKeys = array();
        for($i = 0; $i < count($inputParams[$index]); ++$i){
            $bindKeys[] = self::key_prefix . $name . $i;
        }
        return join(',', $bindKeys);
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
        foreach($this->names as $name => $pos){
            $index = 0;
            foreach($value[$pos] as $v){
                $stmt->bindValue(self::key_prefix . $name . ($index++), $v);
            }
        }
    }
}
