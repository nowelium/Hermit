<?php

/**
 * @author nowelium
 */
class HermitSqlParameterClassHash extends HermitSqlParameterHash {
    private $reflector;
    public function __construct(ReflectionClass $reflector){
        $this->reflector = $reflector;
    }
    public function bind(PDOStatement $stmt, $value){
        foreach($value as $index => $obj){
            foreach($this->bindKeys as $index => $key){
                $method = $this->reflector->getMethod('get' . ucfirst($key));
                $stmt->bindValue(':' . $key, $method->invoke($obj));
            }
        }
    }
}
