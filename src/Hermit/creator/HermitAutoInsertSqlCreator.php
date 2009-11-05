<?php

/**
 * @author nowelium
 */
class HermitAutoInsertSqlCreator implements HermitSqlCreator {
    private $insert;
    public function initialize(PDO $pdo, ReflectionMethod $method, HermitAnnote $annote){
        $meta = HermitDatabaseMetaFactory::get($pdo);
        $table = $annote->getTable();
        $info = $meta->getTableInfo($table);
        $columns = $info->getColumns();
        $insert = 'INSERT INTO';
        $insert .= ' ';
        $insert .= $table;
        $insert .= ' (';
        $insert .= join(', ', $columns);
        $insert .= ') VALUES (';

        $begin = false;
        foreach($columns as $column){
            $begin = true;
            $insert .= '/*' . $column . '*/';
            $insert .= '"' . $column . '"';
            $insert .= ',';
        }
        if($begin){
            $insert = substr($insert, 0, -1);
        }
        $insert .= ')';
        $this->insert = $insert;
    }
    public function createSql(){
        return $this->insert;
    }
}
