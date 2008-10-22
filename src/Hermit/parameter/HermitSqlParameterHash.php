<?php

/**
 * @author nowelium
 */
class HermitSqlParameterHash extends HermitSqlParameter {
    protected $names = array();
    protected $bindKeys = array();
    public function add($name, $index){
        $this->names[$name] = $index;
    }
    public function replace($key, $name, $defaultValue){
        $this->bindKeys[] = $name;
        return ':' . $name;
    }
    public function bind(PDOStatement $stmt, $value){
        $logger = HermitLoggerManager::getLogger();
        if($logger->isDebugEnabled()){
            $buf = '';
            foreach($this->names as $name => $pos){
                $buf .= ':' . $name . ' => ' . $value[$pos];
            }
            $logger->debug('{%s} statement binds parameter {:key => param} = %s', __CLASS__, $buf);
        }
        foreach($this->names as $name => $pos){
            $stmt->bindValue(':' . $name, $value[$pos]);
        }
    }
}
