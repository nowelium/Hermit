<?php

interface EmployeeDao {
    const TABLE = 'EMP';

    const getAllEmployeeList_SQL = 'SELECT * FROM EMP';
    public function getAllEmployeeList();

    const getEmployee_QUERY = 'EMPNO = /*empno*/"7369" and ENAME = /*ename*/"SMITH"';
    public function getEmployee(Employee $emp);

    const getEmployeeByEmpNo_QUERY = 'EMPNO = /*empno*/"7369"';
    public function getEmployeeByEmpNo($empno);


    const getEmployeeAndDepartment_VALUE_TYPE = 'ASSOC';
    public function getEmployeeAndDepartment();
}
