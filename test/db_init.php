<?php

function db_init(PDO $pdo){
    $name = HermitDatabaseMetaFactory::getDatabaseName($pdo);
    $_db_init = 'db_' . $name . '_init';
    $_db_init($pdo);
    
    $sqlpath = dirname(__FILE__) . '/resource/test-' . $name . '.sql.php';
    if(file_exists($sqlpath)){
        require $sqlpath;
        $_db_query = 'db_' . $name . '_query_test';
        $_db_query($pdo);
    } else {
        $sqlpath = dirname(__FILE__) . '/resource/test-' . $name . '.sql';
        try {
            $pdo->beginTransaction();
            $pdo->exec(file_get_contents($sqlpath));
            $pdo->commit();
        } catch(PDOException $e){
            $pdo->rollback();
            echo (string) $e, PHP_EOL;
            throw new RuntimeException(__FUNCTION__ . ' does not complete');
        }
    }
    $sqlpath = dirname(__FILE__) . '/resource/procedure-' . $name . '.sql.php';
    if(file_exists($sqlpath)){
        require $sqlpath;
        $_db_query = 'db_' . $name . '_query_procedure';
        $_db_query($pdo);
    } else {
        $sqlpath = dirname(__FILE__) . '/resource/procedure-' . $name . '.sql';
        try {
            $pdo->beginTransaction();
            $pdo->exec(file_get_contents($sqlpath));
            $pdo->commit();
        } catch(PDOException $e){
            $pdo->rollback();
            echo (string) $e, PHP_EOL;
            throw new RuntimeException(__FUNCTION__ . ' does not complete');
        }
    }
}

function db_mysql_init(PDO $pdo){
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
    $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
}
