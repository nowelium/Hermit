<?php

/**
 * @author nowelium
 */
class HermitMySQLDatabaseMeta implements HermitDatabaseMeta {
    
    const USING_DB_NAME_SQL = 'SELECT database()';
    const TABLE_INFO_SQL = 'SELECT * FROM %s LIMIT 0';
    const PROCEDIRE_INFO_SQL = 'SELECT param_list, returns FROM mysql.proc WHERE db = :db AND name = :name';
    
    private $tables = array();
    private $procedures = array();
    private $pdo;
    private $databaseName;
    public function initConnection(PDO $pdo){
        $this->pdo = $pdo;
        $this->databaseName = $this->getUsingDatabaseName();
    }
    public function getTableInfo($table){
        if(isset($this->tables[$table])){
            return $this->tables[$table];
        }
        
        $sql = sprintf(self::TABLE_INFO_SQL, $table);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
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
        $stmt->closeCursor();
        unset($stmt);
        return $this->tables[$table] = $info;
    }
    public function getProcedureInfo($procedure){
        if(isset($this->procedures[$procedure])){
            return $this->procedures[$procedure];
        }
        $stmt = $this->pdo->prepare(self::PROCEDIRE_INFO_SQL);
        $stmt->execute(array(':db' => $this->databaseName, ':name' => $procedure));
        $paramList = $stmt->fetchColumn(0);
        if(false === $paramList){
            throw new InvalidArgumentException('not found procedure name: ' . $procedure . ' in db: ' . $this->databaseName);
        }
        
        // replace (...) labels
        // e.g. IN _hoge DECIMAL(10, 5), OUT _foo VARCHAR(20)
        $paramList = preg_replace('/(\(([^\)]+)\))/', '', $paramList);

        $info = new HermitProcedureInfo;
        $info->setName($procedure);
        $chunk = array_map('trim', explode(',', $paramList));
        foreach($chunk as $field){
            $sp = preg_split('/\s+/', $field);

            $inoutType = null;
            $paramName = null;
            $paramType = null;

            $upper = strtoupper($sp[0]);
            if(in_array($upper, array('IN', 'OUT', 'INOUT'), true)){
                $inoutType = $upper;
                $paramName = $sp[1];
                $paramType = $sp[2];
            } else {
                // default IN: http://dev.mysql.com/doc/refman/5.1/ja/create-procedure.html
                $inoutType = 'IN';
                $paramName = $sp[0];
                $paramType = $sp[1];
            }
            $info->addParamName($paramName);
            $info->putParamType($paramName, $paramType);
            if(strcmp('IN', $inoutType) === 0){
                $info->putInType($paramName);
            } else if(strcmp('OUT', $inoutType) === 0){
                $info->putOutType($paramName);
            } else {
                $info->putInOutType($paramName);
            }
        }

        $stmt->closeCursor();
        unset($stmt);
        return $this->procedures[$procedure] = $info;
    }

    protected function getUsingDatabaseName(){
        $stmt = $this->pdo->prepare(self::USING_DB_NAME_SQL);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        
        $stmt->closeCursor();
        unset($stmt);
        return $result;
    }
}
