<?php

/**
 * @author nowelium
 */
abstract class HermitSqlParameter {
    public function match($matches){
        return $this->replace($matches[1], $matches[2], $matches[3]);
    }
    public abstract function replace($key, $name, $defaultValue);
    public abstract function bind(PDOStatement $stmt, $value);
}
