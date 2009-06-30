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
    
    /**
     * @param PDO $pdo
     * @param HermitProcedureParameter $parameter
     * @return HermitResultSet
     */
    public static function create(PDO $pdo, ReflectionMethod $method, HermitAnnote $annote, HermitProcedureParameter $parameter){
        $dbms = HermitDatabaseMetaFactory::getDbms($pdo);
        if(isset(self::$resultsets[$dbms])){
            $className = self::$resultsets[$dbms];
            return new $className($method, $annote, $parameter);
        }
        return new HermitProcedureResultSet($method, $annote, $parameter);
    }
}
