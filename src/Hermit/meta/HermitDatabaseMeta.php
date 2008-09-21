<?php

/**
 * @author nowelium
 */
interface HermitDatabaseMeta {
    public function __construct(PDO $pdo);
    public function getTableInfo($table);
    public function getProcedureInfo($procedure);
}
