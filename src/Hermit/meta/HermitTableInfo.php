<?php

/**
 * @author nowelium
 */
class HermitTableInfo implements Serializable {

    private $pk = array();
    private $columns = array();
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

    public function addPrimaryKey($name){
        $this->pk[] = $name;
    }
    public function addColumn($name){
        $this->columns[] = $name;
    }
    public function putType($name, $type){
        $this->types[$name] = $type;
    }
    public function getPrimaryKeys(){
        return $this->pk;
    }
    public function isPrimaryKey($name){
        return isset($this->pk[$name]);
    }
    public function getColumns(){
        return $this->columns;
    }
    public function getType($name){
        return $this->types[$name];
    }
}
