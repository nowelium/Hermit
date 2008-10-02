<?php
require dirname(__FILE__) . '/setup.php';
require dirname(__FILE__) . '/db_init.php';

interface PROC {
    const callIN_OUT_PROCEDURE = 'PROC_IN_OUT';
    public function callIN_OUT(HermitParam $param);

    const callINOUT_PROCEDURE = 'PROC_INOUT';
    public function callINOUT(HermitParam $param);

    const callIN_OUT_OUT_PROCEDURE = 'PROC_IN_OUT_OUT';
    public function callIN_OUT_OUT(HermitParam $param);

    const callIN_MULTI_PROCEDURE = 'PROC_IN_MULTIRESULT';
    const callIN_MULTI_VALUE_TYPE = 'OBJ';
    public function callIN_MULTI(HermitParam $param);

    const callIN_IN_MULTI_PROCEDURE = 'PROC_IN_IN_MULTIRESULT';
    const callIN_IN_MULTI_VALUE_TYPE = 'ASSOC';
    public function callIN_IN_MULTI(HermitParam $param);

    const callIN_OUT_MULTI_PROCEDURE = 'PROC_IN_OUT_MULTIRESULT';
    const callIN_OUT_MULTI_VALUE_TYPE = 'OBJ';
    public function callIN_OUT_MULTI(HermitParam $param);

    const callINOUT_MULTI_PROCEDURE = 'PROC_INOUT_MULTIRESULT';
    const callINOUT_MULTI_VALUE_TYPE = 'OBJ';
    public function callINOUT_MULTI(HermitParam $param);

    const callOUT_MULTI_PROCEDURE = 'PROC_OUT_MULTIRESULT';
    const callOUT_MULTI_VALUE_TYPE = 'OBJ';
    public function callOUT_MULTI(HermitParam $param);

    const callNOSPEC_PARAM_PROCEDURE = 'PROC_NOSPEC_PARAM';
    const callNOSPEC_PARAM_VALUE_TYPE = 'OBJ';
    public function callNOSPEC_PARAM(HermitParam $param);
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
{
    $param = new HermitParam;
    $param->sales = 5000;

    $result = $hermit->callINOUT($param);

    $test->ok($result === null);
    $test->is($param->sales, 1000);

    $result2 = $hermit->callINOUT($param);
    $test->ok($result === null);
    $test->is($param->sales, 200);
}
{
    $param = new HermitParam;
    $param->sales = 300;

    $result = $hermit->callIN_OUT_OUT($param);
    $test->ok($result === null);
    $test->ok($param->tax !== null);
    $test->ok($param->total !== null);
    $test->is($param->tax, 60);
    $test->is($param->total, 360);
}
{
    $param = new HermitParam;
    $param->set('IN_MGR', 7902);

    $result = $hermit->callIN_MULTI($param);
    $test->ok(is_array($result));
    $test->is(count($result), 3);
    // same results... 0 to 3
    {
        $test->is(count($result[0]), 1);
        $rs = $result[0][0];
        $test->is($rs->EMPNO, 7369);
        $test->is($rs->ENAME, 'SMITH');
        $test->is($rs->JOB, 'CLERK');
    }
    {
        $test->is(count($result[1]), 1);
        $rs = $result[1][0];
        $test->is($rs->EMPNO, 7369);
        $test->is($rs->ENAME, 'SMITH');
        $test->is($rs->JOB, 'CLERK');
    }
    {
        $test->is(count($result[2]), 1);
        $rs = $result[2][0];
        $test->is($rs->EMPNO, 7369);
        $test->is($rs->ENAME, 'SMITH');
        $test->is($rs->JOB, 'CLERK');
    }
}
{
    $param = new HermitParam;
    $param->set('IN_MGR_1', '7698');
    $param->set('IN_MGR_2', '7902');

    $result = $hermit->callIN_IN_MULTI($param);
    $test->ok(is_array($result));
    $test->is(count($result), 2);

    $test->ok(is_array($result[0]));
    $test->is(count($result[0]), 5);
    {
        $rs = $result[0][0];
        $test->is($rs['EMPNO'], 7499);
        $test->is($rs['ENAME'], 'ALLEN');
        $test->is($rs['JOB'], 'SALESMAN');
    }
    {
        $rs = $result[0][1];
        $test->is($rs['EMPNO'], 7521);
        $test->is($rs['ENAME'], 'WARD');
        $test->is($rs['JOB'], 'SALESMAN');
    }
    {
        $rs = $result[0][2];
        $test->is($rs['EMPNO'], 7654);
        $test->is($rs['ENAME'], 'MARTIN');
        $test->is($rs['JOB'], 'SALESMAN');
    }
    {
        $rs = $result[0][3];
        $test->is($rs['EMPNO'], 7844);
        $test->is($rs['ENAME'], 'TURNER');
        $test->is($rs['JOB'], 'SALESMAN');
    }
    {
        $rs = $result[0][4];
        $test->is($rs['EMPNO'], 7900);
        $test->is($rs['ENAME'], 'JAMES');
        $test->is($rs['JOB'], 'CLERK');
    }
    $test->ok(is_array($result[1]));
    $test->is(count($result[1]), 1);
    {
        $rs = $result[1][0];
        $test->is($rs['EMPNO'], 7369);
        $test->is($rs['ENAME'], 'SMITH');
        $test->is($rs['JOB'], 'CLERK');
    }
}
{
    $param = new HermitParam;
    $param->set('IN_MGR_1', 7698);

    $result = $hermit->callIN_OUT_MULTI($param);
    $test->is($param->get('IN_MGR_1'), 7698);
    $test->is($param->get('IN_MGR_2'), 'hello world');

    $test->ok(is_array($result[0]));
    $test->is(count($result[0]), 5);
    {
        $rs = $result[0][0];
        $test->is($rs->EMPNO, 7499);
        $test->is($rs->ENAME, 'ALLEN');
        $test->is($rs->JOB, 'SALESMAN');
    }
    {
        $rs = $result[0][1];
        $test->is($rs->EMPNO, 7521);
        $test->is($rs->ENAME, 'WARD');
        $test->is($rs->JOB, 'SALESMAN');
    }
    {
        $rs = $result[0][2];
        $test->is($rs->EMPNO, 7654);
        $test->is($rs->ENAME, 'MARTIN');
        $test->is($rs->JOB, 'SALESMAN');
    }
    {
        $rs = $result[0][3];
        $test->is($rs->EMPNO, 7844);
        $test->is($rs->ENAME, 'TURNER');
        $test->is($rs->JOB, 'SALESMAN');
    }
    {
        $rs = $result[0][4];
        $test->is($rs->EMPNO, 7900);
        $test->is($rs->ENAME, 'JAMES');
        $test->is($rs->JOB, 'CLERK');
    }
}
{
    $param = new HermitParam;
    $param->set('param', 7900);

    $result = $hermit->callINOUT_MULTI($param);
    $test->is($param->get('param'), 'hello world');
    $test->is(count($result), 1);

    {
        $test->is(count($result[0]), 1);
        $rs = $result[0][0];
        $test->is($rs->ENAME, 'JAMES');
        $test->is($rs->EMPNO, 7900);
    }
}
{
    $param = new HermitParam;

    $result = $hermit->callOUT_MULTI($param);

    $test->is($param->get('param'), 'hello world');
    $test->is(count($result), 1);

    $test->is(count($result[0]), 14);
    $test->is($result[0][0]->MGR, 7902);
    $test->is($result[0][1]->MGR, 7698);
    $test->is($result[0][13]->MGR, 7782);
}
{
    $param = new HermitParam;
    $param->set('param_1', 7499);
    $param->set('param_2', 7521);
    $param->set('param_3', 7654);

    $result = $hermit->callNOSPEC_PARAM($param);

    $test->is(count($result), 3);
    {
        $rs = $result[0];
        $test->is($rs[0]->EMPNO, 7499);
        $test->is($rs[0]->ENAME, 'ALLEN');
    }
    {
        $rs = $result[1];
        $test->is($rs[0]->EMPNO, 7521);
        $test->is($rs[0]->ENAME, 'WARD');
    }
    {
        $rs = $result[2];
        $test->is($rs[0]->EMPNO, 7654);
        $test->is($rs[0]->ENAME, 'MARTIN');
    }
}
