<?php

/**
 * @author nowelium
 */
interface HermitSqlCreator {
    public function initialize(PDO $pdo, ReflectionMethod $method, HermitAnnote $annote);
    public function createSql();
}
