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
        foreach($value as $obj){
            foreach($this->bindKeys as $key){
                if($obj instanceof HermitParam){
                    $stmt->bindValue(':' . $key, $obj->get($key));
                } else {
                    $method = $this->reflector->getMethod('get' . ucfirst($key));
                    $stmt->bindValue(':' . $key, $method->invoke($obj));
                }
            }
        }
    }
}
