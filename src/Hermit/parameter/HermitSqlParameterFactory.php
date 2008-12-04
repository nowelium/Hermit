<?php

/**
 * @author yusuke.hata
 */
abstract class HermitSqlParameterFactory {
    public static function createParameterType(ReflectionMethod $method){
        $numOfParams = $method->getNumberOfParameters();
        if(0 === $numOfParams){
            return new HermitSqlParameterNull;
        }
        $params = $method->getParameters();
        if(1 === $numOfParams){
            return self::createParameterTypeWithRef($params[0]);
        }
        $parameter = new HermitSqlParameterMixed;
        foreach($params as $index => $param){
            $pos = $param->getPosition();
            $parameter->add(self::createParameterTypeWithRef($param), $pos);
        }
        return $parameter;
    }
    protected static function createParameterTypeWithRef(ReflectionParameter $ref){
        if($ref->isArray()){
            $parameter = new HermitSqlParameterSequence;
            $parameter->add($ref->getName(), $ref->getPosition());
            return $parameter;
        }
        $class = $ref->getClass();
        if(is_null($class)){
            $parameter = new HermitSqlParameterHash;
            $parameter->add($ref->getName(), $ref->getPosition());
            return $parameter;
        }
        $parameter = new HermitSqlParameterClassHash($class);
        $parameter->add($ref->getName(), $ref->getPosition());
        return $parameter;
    }
}