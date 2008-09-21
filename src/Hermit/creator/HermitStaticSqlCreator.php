<?php

/**
 * @author nowelium
 */
class HermitStaticSqlCreator implements HermitSqlCreator {
    private $sql;
    public function __construcy($sql){
        $this->sql = $sql;
    }
    public function initialize(PDO $pdo, ReflectionMethod $method, HermitAnnote $annote){
    }
    public function createSql(PDO $pdo){
        return $this->sql;
    }
}
