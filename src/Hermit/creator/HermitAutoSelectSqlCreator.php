<?php

/**
 * @author nowelium
 */
class HermitAutoSelectSqlCreator implements HermitSqlCreator, HermitAppendableSqlCreator {
    private $select;
    private $query;
    private $limit;
    public function initialize(PDO $pdo, ReflectionMethod $method, HermitAnnote $annote){
        $meta = HermitDatabaseMetaFactory::get($pdo);
        $table = $annote->getTable();
        $info = $meta->getTableInfo($table);
        $columns = $info->getColumns();
        $select = 'SELECT';
        $select .= ' ';
        $select .= join(', ', $columns);
        $select .= ' ';
        $select .= 'FROM';
        $select .= ' ';
        $select .= $table;
        $this->select = $select;
    }
    public function createSql(){
        $sql = '';
        $sql .= $this->select;
        if(null !== $this->query){
            $sql .= ' ';
            $sql .= 'WHERE';
            $sql .= ' ';
            $sql .= $this->query;
        }
        if(null !== $this->limit){
            $sql .= ' ';
            $sql .= 'LIMIT';
            $sql .= ' ';
            $sql .= $this->limit;
        }
        return $sql;
    }
    public function addQuery($queryString){
        $this->query = $queryString;
    }
    public function addLimit($limit){
        $this->limit = $limit;
    }
}
