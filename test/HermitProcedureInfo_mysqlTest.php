<?php
require dirname(__FILE__) . '/setup.php';
require dirname(__FILE__) . '/db_init.php';

$pdo = new PDO('mysql:host=localhost; dbname=hermit_test', 'root', 'password');
db_init($pdo);

$test = new lime_test;
$test->diag(basename(__FILE__));

$dbmeta = new HermitMySQLDatabaseMeta($pdo);
{
    $test->diag(basename(__FILE__) . '::parameter[in:out]');
    $procedureInfo = $dbmeta->getProcedureInfo('PROC_IN_OUT');

    $test->ok($procedureInfo !== null);
    $parameters = array_map('strtolower', $procedureInfo->getParamNames());
    $expect = array('sales', 'tax');
    $test->is(count(array_diff($parameters, $expect)), 0);
    $test->is($procedureInfo->typeofIn('sales'), true);
    $test->is($procedureInfo->typeofOut('sales'), false);
    $test->is($procedureInfo->typeofInOut('sales'), false);
    $test->is($procedureInfo->typeofIn('tax'), false);
    $test->is($procedureInfo->typeofOut('tax'), true);
    $test->is($procedureInfo->typeofInOut('tax'), false);
}
$procedureInfo2 = $dbmeta->getProcedureInfo('PROC_IN_OUT');
$test->ok($procedureInfo === $procedureInfo2, 'same instance');
{
    $test->diag(basename(__FILE__) . '::parameter[in:out:out]');
    $procedureInfo = $dbmeta->getProcedureInfo('PROC_IN_OUT_OUT');
    $parameters = array_map('strtolower', $procedureInfo->getParamNames());
    $expect = array('sales', 'tax', 'total');
    $test->is(count(array_diff($parameters, $expect)), 0);
    $test->is($procedureInfo->typeofIn('sales'), true);
    $test->is($procedureInfo->typeofOut('sales'), false);
    $test->is($procedureInfo->typeofInOut('sales'), false);
    $test->is($procedureInfo->typeofIn('tax'), false);
    $test->is($procedureInfo->typeofOut('tax'), true);
    $test->is($procedureInfo->typeofInOut('tax'), false);
    $test->is($procedureInfo->typeofIn('total'), false);
    $test->is($procedureInfo->typeofOut('total'), true);
    $test->is($procedureInfo->typeofInOut('total'), false);
    $test->ok($procedureInfo !== $procedureInfo2, 'not same instance;cause not same procedure');
}
{
    $test->diag(basename(__FILE__) . '::parameter[in:in]');
    $procedureInfo = $dbmeta->getProcedureInfo('PROC_IN_IN_MULTIRESULT');
    $parameters = array_map('strtoupper', $procedureInfo->getParamNames());
    $expect = array('IN_MGR_1', 'IN_MGR_2');
    $test->is(count(array_diff($parameters, $expect)), 0, 'check uppercase');
    $expect = array('in_mgr_1', 'in_mgr_2');
    $test->is(count(array_diff($parameters, $expect)), 2, 'check uppercase');
    $test->is($procedureInfo->typeofIn('IN_MGR_1'), true);
    $test->is($procedureInfo->typeofOut('IN_MGR_1'), false);
    $test->is($procedureInfo->typeofInOut('IN_MGR_1'), false);
    $test->is($procedureInfo->typeofIn('IN_MGR_2'), true);
    $test->is($procedureInfo->typeofOut('IN_MGR_2'), false);
    $test->is($procedureInfo->typeofInOut('IN_MGR_2'), false);
}
{
    $test->diag(basename(__FILE__) . '::parameter[inout]');
    $procedureInfo = $dbmeta->getProcedureInfo('PROC_INOUT');
    $parameters = array_map('strtolower', $procedureInfo->getParamNames());
    $expect = array('sales');
    $test->is(count(array_diff($parameters, $expect)), 0);
    $test->is($procedureInfo->typeofIn('sales'), false);
    $test->is($procedureInfo->typeofOut('sales'), false);
    $test->is($procedureInfo->typeofInOut('sales'), true);
}
{
    $test->diag(basename(__FILE__) . '::parameter[in]');
    $procedureInfo = $dbmeta->getProcedureInfo('PROC_IN_MULTIRESULT');
    $parameters = array_map('strtoupper', $procedureInfo->getParamNames());
    $expect = array('IN_MGR');
    $test->is(count(array_diff($parameters, $expect)), 0, 'check case insentive');
    $test->is($procedureInfo->typeofIn('IN_MGR'), true);
    $test->is($procedureInfo->typeofOut('IN_MGR'), false);
    $test->is($procedureInfo->typeofInOut('IN_MGR'), false);
}
{
    $test->diag(basename(__FILE__) . '::parameter[out]');
    $procedureInfo = $dbmeta->getProcedureInfo('PROC_OUT_MULTIRESULT');
    $parameters = array_map('strtolower', $procedureInfo->getParamNames());
    $expect = array('param');
    $test->is(count(array_diff($parameters, $expect)), 0);
    $test->is($procedureInfo->typeofIn('param'), false);
    $test->is($procedureInfo->typeofOut('param'), true);
    $test->is($procedureInfo->typeofInOut('param'), false);
}
{
    $test->diag(basename(__FILE__) . '::parameter[]');
    $procedureInfo = $dbmeta->getProcedureInfo('PROC_NOSPEC_PARAM');
    $parameters = array_map('strtolower', $procedureInfo->getParamNames());
    $expect = array('param_1', 'param_2', 'param_3');
    $test->is(count(array_diff($parameters, $expect)), 0);
    $test->is($procedureInfo->typeofIn('param_1'), true);
    $test->is($procedureInfo->typeofOut('param_1'), false);
    $test->is($procedureInfo->typeofInOut('param_1'), false);
    $test->is($procedureInfo->typeofIn('param_2'), true);
    $test->is($procedureInfo->typeofOut('param_2'), false);
    $test->is($procedureInfo->typeofInOut('param_2'), false);
    $test->is($procedureInfo->typeofIn('param_3'), true);
    $test->is($procedureInfo->typeofOut('param_3'), false);
    $test->is($procedureInfo->typeofInOut('param_3'), false);
}
