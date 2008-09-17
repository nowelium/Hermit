<?php

class HermitStaticSqlCreator implements HermitSqlCreator {
    private $sql;
    public function __construct($sql){
        $this->sql = $sql;
    }
    public function createSql(PDO $pdo){
        return $this->sql;
    }
}
