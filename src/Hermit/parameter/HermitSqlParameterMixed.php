<?php

/**
 * @author nowelium
 */
class HermitSqlParameterMixed extends HermitSqlParameter {
    private $index = 0;
    private $parameters = array();
    public function add(HermitSqlParameter $param, $index){
        $this->parameters[$index] = $param;
    }
    protected function hasParameter($name){
        foreach($this->parameters as $param){
            if($param->hasParameter($name)){
                return true;
            }
        }
        return false;
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
        }
    }
    
    public function monoCreate($expression, $statement, $parameterValue){
        foreach($this->parameters as $param){
            $expression = $param->monoCreate($expression, $statement, $parameterValue);
        }
        return $expression;
    }
    public function binoCreate($expression, $trueStatement, $falseStatement, $parameterValue){
        foreach($this->parameters as $param){
            $expression = $param->binoCreate($expression, $trueStatement, $falseStatement, $parameterValue);
        }
        return $expression;
    }
}
