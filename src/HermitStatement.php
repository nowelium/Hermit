<?php

/**
 * @author nowelium
 */
class HermitStatement {
    protected $parameter;
    protected $statement;
    public function __construct(HermitSqlParameter $parameter, PDOStatement $statement){
        $this->parameter = $parameter;
        $this->statement = $statement;
    }
    public function execute($parameterValue = array()){
        $this->parameter->bind($this->statement, $parameterValue);
        return $this->statement->execute();
    }
    public function __call($name, $params){
        return call_user_func_array(array($this->statement, $name), $params);
    }
}
