<?php

/**
 * @author nowelium
 */
class HermitSqlParameterNull extends HermitSqlParameter {
    public function replace($key, $name, $defaultValue){
    }
    public function bind(PDOStatement $stmt, $value){
    }
}
