<?php

/**
 * @author nowelium
 */
class HermitStaticSqlCreator implements HermitSqlCreator {
    private $sql;
    public function __construct($sql){
        $this->sql = $sql;
    }
    public function initialize(PDO $pdo, ReflectionMethod $method, HermitAnnote $annote){
    }
    public function createSql(){
        return $this->sql;
    }
}
