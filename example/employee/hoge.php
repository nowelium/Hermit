<?php

class Hoge {
    public function execute($pdo){
        $dao = Hermit::create($pdo);
        //$result = $dao->getAllEmployeeList();
        //$result = $dao->getEmployeeByEmpNo(7698);
        $emp = new Employee;
        $emp->empno = 7566;
        $emp->ename = 'JONES';
        $result = $dao->getEmployee($emp);
        var_dump($result);
    }
}

$pdo = new PDO('sqlite:' . dirname(__FILE__) . '/resource/employee.db');
$hoge = new Hoge;
$hoge->execute($pdo);
