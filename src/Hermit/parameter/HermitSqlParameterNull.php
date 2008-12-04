<?php

/**
 * @author nowelium
 */
class HermitSqlParameterNull extends HermitSqlParameter {
    protected function hasParameter($name){
        return false;
    }
    public function replace($key, $name, $defaultValue){
    }
    public function bind(PDOStatement $stmt, $value){
    }
    public function monoCreate($expression, $statement, $parameterValue){
    }
    public function binoCreate($expression, $trueStatement, $falseStatement, $parameterValue){
    }
}
