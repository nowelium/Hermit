<?php
require dirname(__FILE__) . '/setup.php';
require dirname(__FILE__) . '/db_init.php';

interface PROC {
    const callIN_OUT_PROCEDURE = 'PROC_IN_OUT';
    public function callIN_OUT(HermitParam $param);
}

$pdo = new PDO('mysql:host=localhost; dbname=hermit_test', 'root', 'password');
db_init($pdo);

$test = new lime_test;
$test->diag(basename(__FILE__));

HermitDataSourceManager::setDefault($pdo);

$hermit = new Hermit('PROC');
{
    $param = new HermitParam;
    $param->sales = 1000;
    $param->tax = -1;
    $result = $hermit->callIN_OUT($param);

    $test->ok($param->tax !== -1);
    $test->is($param->tax, 200);
    $test->ok($result === null);
}
