<?php

/**
 * @author nowelium
 */
class HermitDefaultStatement implements HermitStatement {
    protected $parameter;
    protected $statement;
    public function __construct(HermitSqlParameter $parameter, PDOStatement $statement){
        $this->parameter = $parameter;
        $this->statement = $statement;
    }
    public function __destruct(){
        $this->statement->closeCursor();
        unset($this->statement);
    }
    public function getSqlParameter(){
        return $this->parameter;
    }
    public function execute($parameterValue = array()){
        $this->parameter->bind($this->statement, $parameterValue);
        return $this->statement->execute();
    }
    public function fetch(){
        $args = func_get_args();
        $c = count($args);
        if($c < 1){
            return $this->statement->fetch();
        }
        if($c < 2){
            return $this->statement->fetch($args[0]);
        }
        return call_user_func_array(array($this->statement, 'fetch'), $args);
    }
    public function __call($name, $params){
        return call_user_func_array(array($this->statement, $name), $params);
    }
}
