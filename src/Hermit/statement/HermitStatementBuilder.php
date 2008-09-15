<?php

/**
 * @author nowelium
 */
class HermitStatementBuilder {
    const REGEX = '/(\/\*(\w+)\*\/)((\'|")(\w+)(\'|"))/m';
    public static function prepare(PDO $pdo, ReflectionMethod $method, $sql){
        $parameterType = self::createParameterType($method);
        $sql = preg_replace_callback(self::REGEX, array($parameterType, 'match'), $sql);
        return new HermitStatement($parameterType, $pdo->prepare($sql));
    }
    protected static function createParameterType(ReflectionMethod $method){
        $numOfParams = $method->getNumberOfParameters();
        if($numOfParams < 1){
            return new HermitSqlParameterNull;
        }
        $params = $method->getParameters();
        if($numOfParams == 1){
            return self::createParameterTypeWithIndex($params[0], 0);
        }
        $parameter = new HermitSqlParameterMixed;
        foreach($params as $index => $param){
            $parameter->add(self::createParameterTypeWithIndex($param, $index));
        }
        return $parameter;
    }
    protected static function createParameterTypeWithIndex(ReflectionParameter $ref, $index){
        if($ref->isArray()){
            $parameter = new HermitSqlParameterSequence;
            $parameter->add($ref->getName(), $index);
            return $parameter;
        }
        $class = $ref->getClass();
        if(is_null($class)){
            $parameter = new HermitSqlParameterHash;
            $parameter->add($ref->getName(), $index);
            return $parameter;
        }
        $parameter = new HermitSqlParameterClassHash($class);
        $parameter->add($ref->getName(), $index);
        return $parameter;
    }
}
