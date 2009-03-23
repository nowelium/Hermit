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
        $inputParameters = $this->getInputParameters();
        foreach($this->parameters as $index => $param){
            $param->setInputParameters($inputParameters);
            if($param->hasParameter($name)){
                return $param->replace($key, $name, $defaultValue);
            }
        }
        return $defaultValue;
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
            $result = $param->monoCreate($expression, $statement, $parameterValue);
            if(self::MONO_MATCHED === $result){
                return $result;
            }
        }
        return self::MONO_UNMATCH;
    }
    public function binoCreate($expression, $trueStatement, $falseStatement, $parameterValue){
        foreach($this->parameters as $param){
            $result = $param->binoCreate($expression, $trueStatement, $falseStatement, $parameterValue);
            if(self::BINO_TRUE_MATCHED === $result){
                return $result;
            }
            if(self::BINO_FALSE_MATCHED === $result){
                return $result;
            }
        }
        return self::BINO_UNMATCH;
    }
}
