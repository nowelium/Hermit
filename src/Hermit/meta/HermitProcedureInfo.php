<?php

/**
 * @author nowelium
 */
class HermitProcedureInfo implements Serializable {
    private $names = array();
    private $inout = array();
    private $types = array();

    const IN_TYPE = 1;
    const OUT_TYPE = 2;
    const INOUT_TYPE = 3;

    public function serialize(){
    }
    public function unserialize($serialized){
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
    public function getParamsNames(){
        return $this->names;
    }
    public function getInout($name){
        return $this->input[$name];
    }
    public function getParamType($name){
        return $this->types[$name];
    }
    public function typeofIn($name){
        return self::IN_TYPE === $this->inout[$name];
    }
    public function typeofOut($name){
        return self::OUT_TYPE === $this->inout[$name];
    }
    public function typeofInOut($name){
        return self::INOUT_TYPE === $this->inout[$name];
    }
}
