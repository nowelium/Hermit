<?php

/**
 * @author nowelium
 */
abstract class HermitDatabaseMetaFactory {
    protected static $dbms = array(
        'mysql' => 'HermitMySQLDatabaseMeta',
        'sqlite' => 'HermitSqliteDatabaseMeta'
    );
    protected static $cache = array();
    protected function __construct(){
        // nop
    }
    public static function getDbms(PDO $pdo){
        return $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }
    public static function get(PDO $pdo){
        $driver = self::getDbms($pdo);
        if(!isset(self::$dbms[$driver])){
            throw new RuntimeException('unsupported driver: ' . $driver);
        }
        if(isset(self::$cache[$driver])){
            return self::$cache[$driver];
        }
        $dbmeta = new self::$dbms[$driver]($pdo);
        return self::$cache[$driver] = $dbmeta;
    }
}
