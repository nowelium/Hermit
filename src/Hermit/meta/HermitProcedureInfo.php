<?php

/**
 * @author nowelium
 */
class HermitProcedureInfo implements Serializable {
    const IN_TYPE = 1;
    const OUT_TYPE = 2;
    const INOUT_TYPE = 3;

    private $names = array();
    private $inout = array();
    private $types = array();

    public function serialize(){
        $s = array();
        foreach($this as $property => $value){
            $s[$property] = $value;
        }
        return serialize($s);
    }
    public function unserialize($serialized){
        $unserialized = unserialize($serialized);
        if(!is_array($unserialized)){
            throw new UnexpectedValueException('unserilized value: ' . $serialized);
        }
        foreach($unserialized as $property => $value){
            $this->$property = $value;
        }
    }

    public function addParamName($name){
        $this->names[] = $name;
    }
    public function putInType($name){
        $this->inout[$name] = self::IN_TYPE;
    }
    public function putOutType($name){
        $this->inout[$name] = self::OUT_TYPE;
    }
    public function putInOutType($name){
        $this->inout[$name] = self::INOUT_TYPE;
    }
    public function putParamType($name, $type){
        $this->types[$name] = $type;
    }
    public function getParamNames(){
        return $this->names;
    }
    public function getInout($name){
        if(!array_key_exists($name, $this->inout)){
            throw new InvalidArgumentException('"' . $name . '" has not value in: ' . join(',', array_keys($this->inout)));
        }
        return $this->input[$name];
    }
    public function getParamType($name){
        if(!array_key_exists($name, $this->inout)){
            throw new InvalidArgumentException('"' . $name . '" has not value in: ' . join(',', array_keys($this->inout)));
        }
        return $this->types[$name];
    }
    public function typeofIn($name){
        if(!array_key_exists($name, $this->inout)){
            throw new InvalidArgumentException('"' . $name . '" has not value in: ' . join(',', array_keys($this->inout)));
        }
        return self::IN_TYPE === $this->inout[$name];
    }
    public function typeofOut($name){
        if(!array_key_exists($name, $this->inout)){
            throw new InvalidArgumentException('"' . $name . '" has not value in: ' . join(',', array_keys($this->inout)));
        }
        return self::OUT_TYPE === $this->inout[$name];
    }
    public function typeofInOut($name){
        if(!array_key_exists($name, $this->inout)){
            throw new InvalidArgumentException('"' . $name . '" has not value in: ' . join(',', array_keys($this->inout)));
        }
        return self::INOUT_TYPE === $this->inout[$name];
    }
}
