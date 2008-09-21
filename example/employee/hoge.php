<?php

require dirname(__FILE__) . '/../example.php';
HermitAutoloader::import(dirname(__FILE__));
HermitAutoloader::import(dirname(__FILE__) . '/dao');

class Hoge {
    public function execute(){
        //HermitDaoManager::set(__CLASS__, 'EmployeeDao');
        $dao = new Hermit('EmployeeDao');
        //$result = $dao->getAllEmployeeList();
        $result = $dao->getEmployeeByEmpNo(7698);
        /*
        $emp = new Employee;
        $emp->empno = 7566;
        $emp->ename = 'JONES';
        $result = $dao->getEmployee($emp);
        */
        return $result;
    }
}
$pdo = new PDO('sqlite:' . dirname(__FILE__) . '/resource/employee.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

HermitDataSourceManager::set('EmployeeDao', $pdo);
$hoge = new Hoge;
$result = $hoge->execute();
var_dump($result);
