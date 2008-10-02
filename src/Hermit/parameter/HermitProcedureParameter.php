<?php

/**
 * @author nowelium
 */
class HermitProcedureParameter extends HermitSqlParameterHash {
    protected $info;
    protected $dbms;
    protected $outParams = array();
    protected static $pdoTypes = array(
        'boolean' => PDO::PARAM_BOOL,
        'NULL' => PDO::PARAM_NULL,
        'integer' => PDO::PARAM_INT,
        'string' => PDO::PARAM_STR
    );
    public function __construct(HermitProcedureInfo $info, $dbms){
        $this->info = $info;
        $this->dbms = $dbms;
    }
    public function getOutParameters(){
        return $this->outParams;
    }
    public function replace($key, $name, $defaultValue){
        $this->bindKeys[] = $name;
        if($this->info->typeofOut($name) || $this->info->typeofInOut($name)){
            $this->outParams[] = $name;
        }
        return ':' . $name;
    }
    public function bind(PDOStatement $stmt, $value){
        $param = $value[0];
        $propertyNames = $param->getPropertyNames();

        foreach($this->bindKeys as $index => $key){
            $bindKey = ':' . $key;
            if($this->info->typeofIn($key)){
                if(!in_array($key, $propertyNames)){
                    throw new InvalidArgumentException('param ' . $param . ' has not propery: ' . $key . ' instatement: ' . $stmt->queryString);
                }
                $stmt->bindParam($bindKey, $param->$key);
                continue;
            }
            
            $paramValue = null;
            if(isset($param->$key)){
                $paramValue =  $param->$key;
            }
            if(null === $paramValue){
                $stmt->bindParam($bindKey, $paramValue, PDO::PARAM_NULL | PDO::PARAM_INPUT_OUTPUT);
                continue;
            }
            $gettype = gettype($paramValue);
            $paramType = -1;
            if(isset(self::$pdoTypes[$gettype])){
                $paramType = self::$pdoTypes[$gettype] | PDO::PARAM_INPUT_OUTPUT;
            } else {
                $paramType = PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT;
            }
            $stmt->bindParam($bindKey, $paramValue, $paramType);
        }
    }
    public function hasBindParameters(){
        return 0 < count($this->outParams);
    }
}
