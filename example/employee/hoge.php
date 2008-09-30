<?php

require dirname(__FILE__) . '/../example.php';
HermitAutoloader::import(dirname(__FILE__));
HermitAutoloader::import(dirname(__FILE__) . '/dao');

class Hoge {
    private $dao;
    public function __construct(){
        //HermitDaoManager::set(__CLASS__, 'EmployeeDao');
<<<<<<< .mine
        $dao = new Hermit('EmployeeDao');
        //$result = $dao->getAllEmployeeList();
        //$result = $dao->getEmployeeByEmpNo(7698);
        /*
=======
        $this->dao = new Hermit('EmployeeDao');
    }
    public function getAll(){
        return $this->dao->getAllEmployeeList();
    }
    public function getOne(){
        return $this->dao->getEmployeeByEmpNo(7698);
    }
    public function getEmp(){
>>>>>>> .r20319
        $emp = new Employee;
        $emp->empno = 7566;
        $emp->ename = 'JONES';
<<<<<<< .mine
        $result = $dao->getEmployee($emp);
        */
        $result = $dao->getEmployeeAndDepartment();
        return $result;
=======
        return $this->dao->getEmployee($emp);
>>>>>>> .r20319
    }
}
$pdo = new PDO('sqlite:' . dirname(__FILE__) . '/resource/employee.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

HermitDataSourceManager::set('EmployeeDao', $pdo);
$hoge = new Hoge;
echo '[getAll] -----------------', var_export($hoge->getAll(), true), PHP_EOL;
echo '[getOne] -----------------', var_export($hoge->getOne(), true), PHP_EOL;
echo '[getEmp] -----------------', var_export($hoge->getEmp(), true), PHP_EOL;

