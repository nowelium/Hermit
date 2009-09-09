<?php

/**
 * @author nowelium
 */
interface HermitDatabaseMeta {
    public function initConnection(PDO $pdo);
    public function getTableInfo($table);
    public function getProcedureInfo($procedure);
}
