<?php
require dirname(__FILE__) . '/setup.php';

$test = new lime_test;
$test->diag(basename(__FILE__));

interface Hoge {

    const getA_SQL = '
        SELECT * FROM A WHERE
        /*IF hoge != null*/
            hoge = /*hoge*/
            --ELSE hoge is null
        /*END*/
        /*IF is_number(foo)*/
            foo = /*foo*/
            --ELSE foo is null
        /*END*/
    ';
    public function getA($hoge, $foo);
}

$refClass = new ReflectionClass('Hoge');
$refMethod_getA = $refClass->getMethod('getA');
$sqlCreator = new HermitStaticSqlCreator($refClass->getConstant('getA_SQL'));

$pdo = new PDO('sqlite://:memory:');

$builder = new HermitStatementBuilder($refMethod_getA, $sqlCreator);
$builder->build($pdo);
