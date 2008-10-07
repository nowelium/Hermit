<?php

/**
 * @author nowelium
 */
class HermitAutoDeleteSqlCreator implements HermitSqlCreator {
    private $sql;
    private $query;
    public function initialize(PDO $pdo, ReflectionMethod $method, HermitAnnote $annote){
        $meta = HermitDatabaseMetaFactory::get($pdo);
        $table = $annote->getTable();
        $info = $meta->getTableInfo($table);
        $primaryKeys = $info->getPrimaryKeys();
        
        $sql = 'DELETE';
        $sql .= ' ';
        $sql .= 'FROM';
        $sql .= ' ';
        $sql .= $table;
        $sql .= ' ';
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
