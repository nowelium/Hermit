<?php

/**
 * @author nowelium
 */
class HermitAutoUpdateSqlCreator implements HermitSqlCreator {
    private $sql;
    private $query;
    public function initialize(PDO $pdo, ReflectionMethod $method, HermitAnnote $annote){
        $meta = HermitDatabaseMetaFactory::get($pdo);
        $table = $annote->getTable();
        $info = $meta->getTableInfo($table);
        $primaryKeys = $info->getPrimaryKeys();
        $columns = $info->getColumns();
        
        $sql = 'UPDATE';
        $sql .= ' ';
        $sql .= $table;
        $sql .= ' ';
        $sql .= 'SET';
        $sql .= ' ';
        foreach($columns as $column){
            $sql .= $column . '=';
            $sql .= '/*' . $column . '*/';
            $sql .= '"' . $column . '"';
            $sql .= ',';
        }
        // -1 eq strlen(',')
        $sql = substr($sql, 0, -1);
        
        $sql .= 'WHERE';
        $sql .= ' ';
        foreach($primaryKeys as $pk){
            $sql .= ' ';
            $sql .= $pk . '=';
            $sql .= ' ';
            $sql .= '/*' . $pk . '*/';
            $sql .= '"' . $pk . '"';
            $sql .= ' ';
            $sql .= 'AND';
        }
        $sql = substr($sql, 0, -3);
        $this->sql = $sql;
    }
    public function createSql(){
        $sql = '';
        $sql .= $this->sql;
        if(null !== $this->query){
            $sql .= ' ';
            $sql .= 'WHERE';
            $sql .= ' ';
            $sql .= $this->query;
        }
        return $sql;
    }
    public function addQuery($queryString){
        $this->query = $queryString;
    }
    public function addLimit($limit){
    }
}
