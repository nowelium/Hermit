<?php

/**
 * @author nowelium
 */
abstract class HermitResultSetFactory {
    private static $resultset = array(
        '/List$/i' => 'HermitListResultSet'
    );
    public static function create(ReflectionMethod $method){
        $name = $method->getName();
        foreach(self::$resultset as $regex => $value){
            if(1 === preg_match($regex, $name)){
                return new $value;
            }
        }
        return new HermitDefaultResultSet;
    }
}
