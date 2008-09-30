<?php
require dirname(__FILE__) . '/setup.php';
require dirname(__FILE__) . '/db_init.php';

$pdo = new PDO('mysql:host=localhost; dbname=hermit_test', 'root', 'password');
db_init($pdo);

$test = new lime_test;
$test->diag(basename(__FILE__));

$dbmeta = new HermitMySQLDatabaseMeta($pdo);
$procedureInfo = $dbmeta->getProcedureInfo('PROC_IN_OUT');

$test->ok($procedureInfo !== null);

