<?php

/**
 * @author nowelium
 */
class HermitLazyStatement implements HermitStatement {
    protected $parameter;
    protected $pdo;
    protected $sql;
    protected $statement;
    public function __construct(HermitSqlParameter $parameter, PDO $pdo, $sql){
        $this->parameter = $parameter;
        $this->pdo = $pdo;
        $this->sql = $sql;
    }
    public function __destruct(){
        if(null !== $this->statement){
            $this->statement->closeCursor();
            unset($this->statement);
        }
    }
    public function getSqlParameter(){
        return $this->parameter;
    }
    public function execute($parameterValue = array()){
        if(null !== $this->statement){
            throw new RuntimeException('statement has created');
        }
        $sql = $this->setupSql($parameterValue);
        $this->statement = $this->pdo->prepare($sql);
        $this->parameter->bind($this->statement, $parameterValue);
        return $this->statement->execute();
    }
    public function __call($name, $params){
        if(null === $this->statement){
            throw new RuntimeException('has not begin statement');
        }
        return call_user_func_array(array($this->statement, $name), $params);
    }
}
