<?php

/**
 * @author nowelium
 */
class HermitLazyStatement implements HermitStatement {
    protected $builder;
    protected $parameter;
    protected $pdo;
    protected $sql;
    protected $statement;
    public function __construct(HermitStatementBuilder $builder, HermitSqlParameter $parameter, PDO $pdo, $sql){
        $this->builder = $builder;
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
        //
        // TODO: このあたりはHermitStatementBuilderに委譲したい
        //
        $expBuilder = new HermitExpression($this->parameter, $parameterValue);
        $sqlString = $expBuilder->build($this->sql);
        $this->statement = $this->builder->rebuild($this->parameter, $this->pdo, $sqlString);
        return $this->statement->execute($parameterValue);
    }
    public function __call($name, $params){
        if(null === $this->statement){
            throw new RuntimeException('has not begin statement');
        }
        return call_user_func_array(array($this->statement, $name), $params);
    }
}
