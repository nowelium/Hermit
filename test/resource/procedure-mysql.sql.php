<?php
function db_mysql_query_procedure(PDO $pdo){
try {
    $pdo->beginTransaction();

$sql = <<<__SQL__
DROP PROCEDURE IF EXISTS PROC_IN_OUT;
__SQL__;
$pdo->exec($sql);
$sql = <<<__SQL__
DROP PROCEDURE IF EXISTS PROC_INOUT;
__SQL__;
$pdo->exec($sql);
$sql = <<<__SQL__
DROP PROCEDURE IF EXISTS PROC_IN_OUT_OUT;
__SQL__;
$pdo->exec($sql);
$sql = <<<__SQL__
DROP PROCEDURE IF EXISTS PROC_IN_MULTIRESULT;
__SQL__;
$pdo->exec($sql);
$sql = <<<__SQL__
DROP PROCEDURE IF EXISTS PROC_IN_IN_MULTIRESULT;
__SQL__;
$pdo->exec($sql);
$sql = <<<__SQL__
DROP PROCEDURE IF EXISTS PROC_IN_OUT_MULTIRESULT;
__SQL__;
$pdo->exec($sql);
$sql = <<<__SQL__
DROP PROCEDURE IF EXISTS PROC_INOUT_MULTIRESULT;
__SQL__;
$pdo->exec($sql);
$sql = <<<__SQL__
DROP PROCEDURE IF EXISTS PROC_OUT_MULTIRESULT;
__SQL__;
$pdo->exec($sql);
$sql = <<<__SQL__
DROP PROCEDURE IF EXISTS PROC_NOSPEC_PARAM;
__SQL__;
$pdo->exec($sql);

//$sql = <<< __SQL__
//__SQL__;
//$stmt = $pdo->query($sql);

$sql = <<<__SQL__
CREATE PROCEDURE PROC_IN_OUT(IN sales INTEGER, OUT tax INTEGER)
begin
    set tax = sales * 0.2;
end;
/
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

$sql = <<<__SQL__
CREATE PROCEDURE PROC_INOUT(INOUT sales INTEGER)
begin
    set sales = sales * 0.2;
end;
/
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);

 
$sql = <<<__SQL__
CREATE PROCEDURE PROC_IN_OUT_OUT(IN sales INTEGER, OUT tax INTEGER, OUT total INTEGER)
begin
    set tax = sales * 0.2;
    set total = sales * 1.2;
end;
/
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);


$sql = <<<__SQL__
CREATE PROCEDURE PROC_IN_MULTIRESULT(IN IN_MGR VARCHAR(50))
begin
  select * from EMP where MGR = IN_MGR;
  select * from EMP where MGR = IN_MGR;
  select * from EMP where MGR = IN_MGR;
end;
/
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);


$sql = <<<__SQL__
CREATE PROCEDURE PROC_IN_IN_MULTIRESULT(IN IN_MGR_1 VARCHAR(50), IN IN_MGR_2 VARCHAR(50))
begin
  select * from EMP where MGR = IN_MGR_1;
  select * from EMP where MGR = IN_MGR_2;
end;
/
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);


$sql = <<<__SQL__
CREATE PROCEDURE PROC_IN_OUT_MULTIRESULT(IN IN_MGR_1 VARCHAR(50), OUT IN_MGR_2 VARCHAR(50))
begin
  select * from EMP where MGR = IN_MGR_1;
  select * from EMP where MGR = IN_MGR_1 into IN_MGR_2;
end;
/
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);


$sql = <<<__SQL__
CREATE PROCEDURE PROC_INOUT_MULTIRESULT(INOUT param VARCHAR(50))
begin
  select * from EMP where empno = param into param;
end;
/
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);


$sql = <<<__SQL__
CREATE PROCEDURE PROC_OUT_MULTIRESULT(OUT param VARCHAR(50))
begin
  select * from EMP into param;
  select * from EMP;
end;
/
__SQL__;
$stmt = $pdo->query($sql);
$stmt->closeCursor();
unset($stmt);


$sql = <<<__SQL__
CREATE PROCEDURE PROC_NOSPEC_PARAM(param_1 INTEGER, param_2 INTEGER, param_3 INTEGER)
begin
  select * from EMP where empno = param_1;
  select * from EMP where empno = param_2;
  select * from EMP where empno = param_3;
end;
/
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

