<?php

class HermitMySQLDatabaseMeta implements HermitDatabaseMeta {
    const TABLE_INFO_SQL = 'SELECT * FROM :TABLE LIMIT 0';
    private $tables = array();
    private $pdo;
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }
    public function getTableInfo($table){
        if(isset($this->tables[$table])){
            return $this->tables[$table];
        }

        $stmt = $this->pdo->prepare(self::TABLE_INFO_SQL);
        $stmt->execute(array(':TABLE' => $table));
        $count = $stmt->columnCount();
        $info = new HermitTableInfo;
        for($i = 0; $i < $count; ++$i){
            $meta = $stmt->getColumnMeta($i);
            $columnName = $meta['name'];
            $columnType = $meta['pdo_type'];

            $info->addColumn($columnName);
            $info->putType($columnName, $columnType);
            if(in_array('primary_key', $meta['flags'])){
                $info->addPrimaryKey($columnName);
            }
        }
        return $this->tables[$table] = $info;
    }
    public function getProcedureInfo($procedure){
        throw new RuntimeException('T.B.D');
    }
}
