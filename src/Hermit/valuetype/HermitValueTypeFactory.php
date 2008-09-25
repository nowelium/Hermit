<?php

/**
 * @author nowelium
 */
abstract class HermitValueTypeFactory {
    protected static $types = array(
        'array' => 'HermitArrayValueType',
        'obj' => 'HermitObjectValueType'
    );
    public static function create($value){
        if(null === $value){
            return new HermitNopValueType;
        }
        if(!isset(self::$types[$value])){
            return new HermitNopValueType;
        }
        return new self::$types[$value];
    }
}

