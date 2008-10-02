<?php

/**
 * @author nowelium
 */
class HermitParam extends stdClass {
    public function set($name, $value){
        $this->$name = $value;
    }
    public function get($name){
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
