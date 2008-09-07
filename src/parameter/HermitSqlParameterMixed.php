<?php

/**
 * @author nowelium
 */
class HermitSqlParameterMixed extends HermitSqlParameter {
    private $parameters = array();
    public function add(HermitSqlParameter $param){
        $this->parameters[] = $param;
    }
    public function replace($key, $name, $defaultValue){
    }
    public function bind(PDOStatement $stmt, $value){
    }
}
