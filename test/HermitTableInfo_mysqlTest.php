<?php
require dirname(__FILE__) . '/setup.php';
require dirname(__FILE__) . '/db_init.php';

$pdo = new PDO('mysql:host=localhost; dbname=hermit_test', 'root', 'password');
db_init($pdo);

$test = new lime_test;
$test->diag(basename(__FILE__));

$dbmeta = new HermitMySQLDatabaseMeta($pdo);
$tableInfo = $dbmeta->getTableInfo('EMP');

$test->isa_ok($tableInfo, 'HermitTableInfo', 'typeof HermitTableInfo');
$test->is(count($tableInfo->getPrimaryKeys()), 1, 'EMP table primary key is empno only');
$test->ok(in_array('empno', array_map('strtolower', array_values($tableInfo->getPrimaryKeys()))));

$tableInfo_hoge = $dbmeta->getTableInfo('EMP');
$test->ok($tableInfo === $tableInfo_hoge, 'same instance');
$test->is(spl_object_hash($tableInfo), spl_object_hash($tableInfo_hoge), 'same instance hash');

$columns = array_map('strtolower', array_values($tableInfo->getColumns()));
$expect = array('empno', 'ename', 'job', 'mgr', 'hiredate', 'sal', 'comm', 'deptno', 'tstamp');
$test->is(count(array_diff($expect, $columns)), 0);
