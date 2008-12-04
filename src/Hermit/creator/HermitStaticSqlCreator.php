<?php

/**
 * @author nowelium
 */
class HermitStaticSqlCreator extends HermitAutoSelectSqlCreator {
    public function __construct($sql){
        $this->select = $sql;
    }
    public function initialize(PDO $pdo, ReflectionMethod $method, HermitAnnote $annote){
        // nop
    }
}
