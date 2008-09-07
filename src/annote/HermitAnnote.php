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
    public abstract function hasSql($name);
    public abstract function getSql($name, $instance = null);
    public abstract function hasQuery($name);
    public abstract function getQuery($name, $instance = null);
    public abstract function hasFile($name);
    public abstract function getFile($name, $instance = null);
    public abstract function hasPath($name);
    public abstract function getPath($name, $instance = null);
    public abstract function hasDelegate($name);
    public abstract function getDelegate($name, $instance = null);
    public static final function create(ReflectionClass $reflector){
        return new HermitAnnoteConst($reflector);
    }
}
