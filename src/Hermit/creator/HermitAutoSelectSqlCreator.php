<?php

/**
 * @author nowelium
 */
class HermitAutoSelectSqlCreator implements HermitSqlCreator, HermitAppendableSqlCreator {
    protected $select;
    protected $query;
    protected $order;
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
            $sql .= HermitQueryUtils::addQuery($sql, $this->query);
            $sql .= ' ';
            $sql .= $this->query;
        }
        if(null !== $this->order){
            $sql .= ' ';
            $sql .= $this->order;
        }
        return $sql;
    }
    public function addQuery($queryString){
        $this->query = $queryString;
    }
    public function addOrder($orderString){
        $this->order = $orderString;
    }
}
