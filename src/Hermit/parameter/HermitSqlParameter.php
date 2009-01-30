<?php

/**
 * @author nowelium
 */
abstract class HermitSqlParameter {
    const MONO_MATCHED = '__mono_matched__';
    const MONO_UNMATCH = '__mono_unmatch__';
    const BINO_TRUE_MATCHED = '__bino_true_matched__';
    const BINO_FALSE_MATCHED = '__bino_false_matched__';
    const BINO_UNMATCH = '__bino_unmatch__';
    
    protected $targetClass;
    protected $targetMethod;
    protected $inputParameters = array();
    public final function setInputParameters(array $inputParameters){
        $this->inputParameters = $inputParameters;
    }
    public final function getInputParameters(){
        return $this->inputParameters;
    }
    public final function setTargetClass(ReflectionClass $targetClass){
        $this->targetClass = $targetClass;
    }
    public final function getTargetClass(){
        return $this->targetClass;
    }
    public final function setTargetMethod(ReflectionMethod $targetMethod){
        $this->targetMethod = $targetMethod;
    }
    public final function getTargetMethod(){
        return $this->targetMethod;
    }
    
    public function match($matches){
        if(count($matches) < 4){
            throw new RuntimeException('sql comment was fail: ' . join(',', $matches));
        }
        list($all, $key, $name, $defaultValue) = $matches;
        
        if($this->hasParameter($name)){
            return $this->replace($key, $name, $defaultValue);
        }
        $annote = HermitAnnote::create($this->targetClass);
        $columns = $annote->getColumns();
        $columnsLower = array_map('strtolower', $columns);
        if(in_array(strtolower($name), $columnsLower)){
            return $this->replace($key, $name, $defaultValue);
        }
        return $defaultValue;
    }
    protected abstract function hasParameter($name);
    public abstract function replace($key, $name, $defaultValue);
    public abstract function bind(PDOStatement $stmt, $value);
    
    public abstract function monoCreate($expression, $statement, $parameterValue);
    public abstract function binoCreate($expression, $trueStatement, $falseStatement, $parameterValue);
}
