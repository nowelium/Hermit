<?php

/**
 * @author nowelium
 */
abstract class HermitAnnote {
    private function __construct(){
        // nop
    }
    public abstract function getTable();
    public abstract function hasMethod($name);
    public abstract function getMethod($name);
    public abstract function isProcedureMethod(ReflectionMethod $method);
    public abstract function isInsertMethod(ReflectionMethod $method);
    public abstract function isUpdateMethod(ReflectionMethod $method);
    public abstract function isDeleteMethod(ReflectionMethod $method);
    public abstract function getProcedure(ReflectionMethod $method);
    public abstract function getSql(ReflectionMethod $method, $suffix = null);
    public abstract function getFile(ReflectionMethod $method);
    public abstract function getQuery(ReflectionMethod $method);
    public abstract function getValueType(ReflectionMethod $method);
    public static final function create(ReflectionClass $reflector){
        return new HermitAnnoteConst($reflector);
    }
    public function isSelectMethod(ReflectionMethod $method){
        if($this->isProcedureMethod($method)){
            return false;
        }
        if($this->isInsertMethod($method)){
            return false;
        }
        if($this->isUpdateMethod($method)){
            return false;
        }
        if($this->isDeleteMethod($method)){
            return false;
        }
        return true;
    }
}

