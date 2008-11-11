<?php
require dirname(__FILE__) . '/setup.php';

$test = new lime_test;
$test->diag(basename(__FILE__));

class HogeDto {
    public $hoge;
    public $foo;
}

interface Hoge {
    const getA_SQL = '
        SELECT * FROM A WHERE
        /*IF hoge != null*/
            hoge = /*hoge*/0
            --ELSE hoge is null
        /*END*/
        AND
        /*IF is_numeric(foo)*/
            foo = /*foo*/0
            --ELSE foo is null
        /*END*/
    ';
    public function getA($hoge, $foo);
    
    const getB_SQL = '
      SELECT * FROM B WHERE
      /*IF hoge != null*/
        hoge = /*hoge*/0
        --ELSE hoge is null
      /:*END*/
      AND
      /*IF is_numeric(foo)*/
        foo = /*foo*/0
        --ELSE foo is null
      /*END*/
    ';
    public function getB(HogeDto $dto);
}

$pdo = new PDO('sqlite::memory:');
$pdo->exec('
create table A (
  hoge int,
  foo int
);
');
$pdo->exec('
create table B (
  hoge int,
  foo int
)
');

$refClass = new ReflectionClass('Hoge');

$refMethod_getA = $refClass->getMethod('getA');
$sqlCreatorA = new HermitStaticSqlCreator($refClass->getConstant('getA_SQL'));

$refMethod_getB = $refClass->getMethod('getB');
$sqlCreatorB = new HermitStaticSqlCreator($refClass->getConstant('getB_SQL'));


$builder = new HermitStatementBuilder($refMethod_getA, $sqlCreatorA);
$stmt = $builder->build($pdo);
$stmt->execute(array(200, 'abc'));


$builder = new HermitStatementBuilder($refMethod_getB, $sqlCreatorB);
$stmt = $builder->build($pdo);
$dto = new HogeDto;
$dto->hoge = 200;
$dto->foo = 'abc';
$stmt->execute(array($dto));