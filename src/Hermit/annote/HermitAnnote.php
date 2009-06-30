<?php

/**
 * @author nowelium
 */
abstract class HermitAnnote {
    private function __construct(){
        // nop
    }
    public abstract function getTable();
    public abstract function getColumns();
    public abstract function hasMethod($name);
    public abstract function getMethod($name);
    public abstract function getProcedure(ReflectionMethod $method);
    public abstract function getSql(ReflectionMethod $method, $suffix = null);
    public abstract function getFile(ReflectionMethod $method);
    public abstract function getQuery(ReflectionMethod $method);
    public abstract function getValueType(ReflectionMethod $method);
    public abstract function getBatchMode(ReflectionMethod $method);
    public abstract function isSingleProcedureResult(ReflectionMethod $method);
    public static final function create(ReflectionClass $reflector){
        return new HermitAnnoteConst($reflector);
    }
}
