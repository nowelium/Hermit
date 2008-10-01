<?php

/**
 * @author nowelium
 */
class HermitParam extends stdClass {
    public function getPropertyNames(){
        $names = array();
        foreach($this as $name => $value){
            $names[] = $name;
        }
        return $names;
    }
}
