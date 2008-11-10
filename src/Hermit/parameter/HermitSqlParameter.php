<?php

/**
 * @author nowelium
 */
abstract class HermitSqlParameter {
    public function match($matches){
        if(count($matches) < 4){
            throw new RuntimeException('sql comment was fail: ' . join(',', $matches));
        }
        return $this->replace($matches[1], $matches[2], $matches[3]);
    }
    public abstract function replace($key, $name, $defaultValue);
    public abstract function bind(PDOStatement $stmt, $value);
    
    public abstract function monoCreate($expression, $statement, $parameterValue);
    public abstract function binoCreate($expression, $trueStatement, $falseStatement, $parameterValue);
}
