<?php

/**
 * @author nowelium
 */
abstract class HermitProcedureResultSetFactory {
    private static $resultsets = array(
        'mysql' => 'HermitMySqlProcedureResultSet'
    );
    private function __construct(){
        // nop
    }
    public static function create(PDO $pdo, HermitProcedureParameter $parameter){
        $dbms = HermitDatabaseMetaFactory::getDbms($pdo);
        $rs = null;
        if(isset(self::$resultsets[$dbms])){
            $className = self::$resultsets[$dbms];
            $rs = new $className($parameter);
        } else {
            $rs = new HermitProcedureResultSet($parameter);
        }
        return $rs;
    }
}
