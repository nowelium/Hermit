<?php

/**
 * @author nowelium
 */
class HermitSqliteDatabaseMeta implements HermitDatabaseMeta {
    const TABLE_META_SQL = 'SELECT * FROM %s LIMIT 1';
    const TABLE_INFO_SQL = 'PRAGMA table_info(%s)';
    private $tables = array();
    private $pdo;
    public function initConnection(PDO $pdo){
        $this->pdo = $pdo;
    }
    public function getTableInfo($table){
        if(isset($this->tables[$table])){
            return $this->tables[$table];
        }

        $columnMeta = $this->getColumnMetas($table);
        $sql = sprintf(self::TABLE_INFO_SQL, $table);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $info = new HermitTableInfo;
        while($row = $stmt->fetch()){
            $columnName = $row['name'];
            $meta = $columnMeta[$columnName];

            $info->addColumn($columnName);
            $info->putType($columnName, $meta['pdo_type']);
            if(1 === (int) $row['pk']){
                $info->addPrimaryKey($columnName);
            }
        }
        unset($stmt);
        return $this->tables[$table] = $info;
    }
    public function getProcedureInfo($procedure){
        throw new RuntimeException('T.B.D');
    }
    protected function getColumnMetas($table){
        $sql = sprintf(self::TABLE_META_SQL, $table);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $c = $stmt->columnCount();
        $meta = array();
        for($i = 0; $i < $c; ++$i){
            $m = $stmt->getColumnMeta($i);
            $meta[$m['name']] = $m;
        }
        unset($stmt);
        return $meta;
    }
}
