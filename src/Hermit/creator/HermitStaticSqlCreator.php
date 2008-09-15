<?php

class HermitStaticSqlCreator implements HermitSqlCreator {
    private $sql;
    public function __construct(ReflectionMethod $method, $sql){
        $this->sql = $sql;
    }
    public function createSql(){
        return $this->sql;
    }
}
