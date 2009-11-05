<?php

/**
 * @author nowelium
 */
class HermitAutoDeleteSqlCreator implements HermitSqlCreator, HermitAppendableSqlCreator {
    protected $sql;
    protected $query;
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

        $begin = false;
        foreach($primaryKeys as $pk){
            $begin = true;
            $sql .= ' ';
            $sql .= $pk . '=';
            $sql .= ' ';
            $sql .= '/*' . $pk . '*/';
            $sql .= '"' . $pk . '"';
            $sql .= ' ';
            $sql .= 'AND';
        }
        if($begin){
            $sql = substr($sql, 0, -3);
        }
        $this->sql = $sql;
    }
    public function createSql(){
        $sql = '';
        $sql .= $this->sql;
        if(null !== $this->query){
            $sql .= HermitQueryUtils::addQuery($sql, $this->query);
            $sql .= ' ';
            $sql .= $this->query;
        }
        return $sql;
    }
    public function addQuery($queryString){
        $this->query = $queryString;
    }
    public function addOrder($order){
    }
}
