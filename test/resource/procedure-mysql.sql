DROP PROCEDURE IF EXISTS PROC_IN_OUT;
DROP PROCEDURE IF EXISTS PROC_INOUT;
DROP PROCEDURE IF EXISTS PROC_IN_OUT_OUT;
DROP PROCEDURE IF EXISTS PROC_IN_MULTIRESULT;
DROP PROCEDURE IF EXISTS PROC_IN_IN_MULTIRESULT;
DROP PROCEDURE IF EXISTS PROC_IN_OUT_MULTIRESULT;
DROP PROCEDURE IF EXISTS PROC_INOUT_MULTIRESULT;
DROP PROCEDURE IF EXISTS PROC_OUT_MULTIRESULT;
DROP PROCEDURE IF EXISTS PROC_NOSPEC_PARAM;

delimiter /

CREATE PROCEDURE PROC_IN_OUT(IN sales INTEGER, OUT tax INTEGER)
begin
    set tax = sales * 0.2;
end;
/
 
CREATE PROCEDURE PROC_INTOUT(INOUT sales INTEGER)
begin
    set sales = sales * 0.2;
end;
/
 
CREATE PROCEDURE PROC_IN_OUT_OUT(IN sales INTEGER, OUT tax INTEGER, OUT total INTEGER)
begin
    set tax = sales * 0.2;
    set total = sales * 1.2;
end;
/

CREATE PROCEDURE PROC_IN_MULTIRESULT(IN IN_MGR VARCHAR(50))
begin
  select * from EMP where MGR = IN_MGR;
  select * from EMP where MGR = IN_MGR;
  select * from EMP where MGR = IN_MGR;
end;
/

CREATE PROCEDURE PROC_IN_IN_MULTIRESULT(IN IN_MGR_1 VARCHAR(50), IN IN_MGR_2 VARCHAR(50))
begin
  select * from EMP where MGR = IN_MGR_1;
  select * from EMP where MGR = IN_MGR_2;
end;
/

CREATE PROCEDURE PROC_IN_OUT_MULTIRESULT(IN IN_MGR_1 VARCHAR(50), OUT IN_MGR_2 VARCHAR(50))
begin
  select * from EMP where MGR = IN_MGR_1;
  select * from EMP where MGR = IN_MGR_1 into IN_MGR_2;
end;
/

CREATE PROCEDURE PROC_INOUT_MULTIRESULT(INOUT param VARCHAR(50))
begin
  select * from EMP where empno = param into param;
end;
/

CREATE PROCEDURE PROC_OUT_MULTIRESULT(OUT param VARCHAR(50))
begin
  select * from EMP into param;
  select * from EMP;
end;
/

CREATE PROCEDURE PROC_NOSPEC_PARAM(param_1 INTEGER, param_2 INTEGER, param_3 INTEGER)
begin
  select * from EMP where empno = param_1;
  select * from EMP where empno = param_2;
  select * from EMP where empno = param_3;
end;
/
