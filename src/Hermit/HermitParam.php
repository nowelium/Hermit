<?php

/**
 * @author nowelium
 */
class HermitParam extends stdClass {
    private $__accessKeys__;
    protected static function accessKeys(HermitParam $target){
        $ref = new ReflectionObject($target);
        return array_flip($ref->getConstants());
    }
    protected static function accessValue(HermitParam $target, $name){
        $target->checkAccessKey();
        if(isset($target->__accessKeys__[$name])){
            $name = preg_replace('/_KEY$/', '', $target->__accessKeys__[$name]);
        }
        return $name;
    }
    public function __init__(){
        $this->__accessKeys__ = self::accessKeys($this);
    }
    public function checkAccessKey(){
        if(null === $this->__accessKeys__){
            $this->__accessKeys__ = self::accessKeys($this);
        }
    }
    public function issetValue($name){
        $name = self::accessValue($this, $name);
        return isset($this->$name);
    }
    public function set($name, $value){
        $name = self::accessValue($this, $name);
        $this->$name = $value;
    }
    public function &get($name){
        $name = self::accessValue($this, $name);
        return $this->$name;
    }
    public function getPropertyNames(){
        $names = array();
        foreach($this as $name => $value){
            $names[] = $name;
        }
        return $names;
    }
    public function __toString(){
        $tos = array();
        foreach($this as $name => $value){
            $tos[] = $name . '=>' . $value;
        }
        return __CLASS__ . ' {' . join(',', $tos) . '}';
    }
}
