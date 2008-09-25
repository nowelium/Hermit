<?php

/**
 * @author nowelium
 */
class HermitSqlParameterMixed extends HermitSqlParameter {
    private $index = 0;
    private $parameters = array();
    public function add(HermitSqlParameter $param){
        $this->parameters[] = $param;
    }
    public function replace($key, $name, $defaultValue){
        return $this->parameters[$this->index++]->replace($key, $name, $defaultValue);
    }
    public function bind(PDOStatement $stmt, $value){
        if(!is_array($value)){
          throw new InvalidParameterException('is not array: ' . var_exoprt($value, true));
        }
        
        $values = (array) $value;
        foreach($this->parameters as $param){
            $param->bind($stmt, $values);
            array_shift($values);
        }
    }
}

