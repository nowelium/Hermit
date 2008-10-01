<?php
function db_mysql_query_test(PDO $pdo){
try {
$pdo->beginTransaction();

$sql = <<<__SQL__
DROP TABLE IF EXISTS EMP;
__SQL__;
$pdo->exec($sql);

$sql = <<<__SQL__
DROP TABLE IF EXISTS DEPT;
__SQL__;
$pdo->exec($sql);

$sql = <<<__SQL__
CREATE TABLE EMP(
    EMPNO INTEGER(4) NOT NULL PRIMARY KEY,
    ENAME VARCHAR(10),
    JOB VARCHAR(9),
    MGR INTEGER(4),
    HIREDATE DATE,
    SAL NUMERIC(7, 2),
    COMM NUMERIC(7, 2),
    DEPTNO INTEGER(2),
    TSTAMP TIMESTAMP
);
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);


$sql = <<<__SQL__
INSERT INTO EMP VALUES(7369,'SMITH','CLERK',7902,'1980-12-17',800,NULL,20,'2000-01-01 00:00:00.0');
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO EMP VALUES(7499,'ALLEN','SALESMAN',7698,'1981-02-20',1600,300,30,'2000-01-01 00:00:00.0');
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO EMP VALUES(7521,'WARD','SALESMAN',7698,'1981-02-22',1250,500,30,'2000-01-01 00:00:00.0');
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO EMP VALUES(7566,'JONES','MANAGER',7839,'1981-04-02',2975,NULL,20,'2000-01-01 00:00:00.0');
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO EMP VALUES(7654,'MARTIN','SALESMAN',7698,'1981-09-28',1250,1400,30,'2000-01-01 00:00:00.0');
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO EMP VALUES(7698,'BLAKE','MANAGER',7839,'1981-05-01',2850,NULL,30,'2000-01-01 00:00:00.0');
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO EMP VALUES(7782,'CLARK','MANAGER',7839,'1981-06-09',2450,NULL,10,'2000-01-01 00:00:00.0');
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO EMP VALUES(7788,'SCOTT','ANALYST',7566,'1982-12-09',3000.0,NULL,20,'2005-01-18 13:09:32.213');
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO EMP VALUES(7839,'KING','PRESIDENT',NULL,'1981-11-17',5000,NULL,10,'2000-01-01 00:00:00.0');
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO EMP VALUES(7844,'TURNER','SALESMAN',7698,'1981-09-08',1500,0,30,'2000-01-01 00:00:00.0');
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO EMP VALUES(7876,'ADAMS','CLERK',7788,'1983-01-12',1100,NULL,20,'2000-01-01 00:00:00.0');
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO EMP VALUES(7900,'JAMES','CLERK',7698,'1981-12-03',950,NULL,30,'2000-01-01 00:00:00.0');
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO EMP VALUES(7902,'FORD','ANALYST',7566,'1981-12-03',3000,NULL,20,'2000-01-01 00:00:00.0');
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO EMP VALUES(7934,'MILLER','CLERK',7782,'1982-01-23',1300,NULL,10,'2000-01-01 00:00:00.0');
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);


$sql = <<<__SQL__
CREATE TABLE DEPT(
    DEPTNO INTEGER(2) NOT NULL PRIMARY KEY,
    DNAME VARCHAR(14),
    LOC VARCHAR(13),
    VERSIONNO INTEGER(8) default 0,
    ACTIVE INTEGER(1)
);
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);


$sql = <<<__SQL__
INSERT INTO DEPT VALUES (10, 'ACCOUNTING', 'NEW YORK', 0, 1);
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO DEPT VALUES (20, 'RESEARCH',   'DALLAS', 0, 1);
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO DEPT VALUES (30, 'SALES',      'CHICAGO', 0, 1);
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
INSERT INTO DEPT VALUES (40, 'OPERATIONS', 'BOSTON', 0, 1);
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);


$pdo->commit();
} catch(PDOException $e){
    $pdo->rollback();
    echo (string) $e, PHP_EOL;
    throw new RuntimeException(__FUNCTION__ . ' does not complete');
}
}
