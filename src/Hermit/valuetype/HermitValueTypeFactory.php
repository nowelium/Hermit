<?php

/**
 * @author nowelium
 */
abstract class HermitValueTypeFactory {
    protected static $valueTypes = array(
        'HermitArrayValueType',
        'HermitObjectValueType'
    );
    public static function create(HermitAnnote $annote, ReflectionMethod $method){
        $value = $annote->getValueType($method);
        foreach(self::$valueTypes as $type){
            if(call_user_func(array($type, 'accept'), $value)){
                return new $type($annote, $method, $value);
            }
        }
        return new HermitNopValueType;
    }
}

