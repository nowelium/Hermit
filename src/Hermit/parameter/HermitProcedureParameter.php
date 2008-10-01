<?php

/**
 * @author nowelium
 */
class HermitProcedureParameter extends HermitSqlParameterHash {
    private $info;
    private $dbms;
    private $outParams = array();
    public function __construct(HermitProcedureInfo $info, $dbms){
        $this->info = $info;
        $this->dbms = $dbms;
    }
    public function replace($key, $name, $defaultValue){
        return call_user_func(array($this, 'replace_' . $this->dbms), $key, $name, $defaultValue);
    }
    public function replace_mysql($key, $name, $defaultValue){
        if($this->info->typeofIn($name)){
            $this->bindKeys[] = $name;
            return ':' . $name;
        }
        if($this->info->typeofOut($name) || $this->info->typeofInOut($name)){
            $this->bindKeys[] = $name;
            $this->outParams[] = $name;
            return '@' . $name;
        }
        throw new RuntimeException('unknown "' . $name . '" parameter');
    }
    public function bind(PDOStatement $stmt, $value){
        return call_user_func(array($this, 'bind_' . $this->dbms), $stmt, $value);
    }

    public function bind_mysql(PDOStatement $stmt, $value){
        $param = $value[0];
        foreach($this->bindKeys as $index => $key){
            $prefix = '';
            if($this->info->typeofIn($key)){
                $stmt->bindParam(':' . $key, $param->$key);
            }
        }
    }

    public function getOutParameters(){
        return $this->outParams;
    }

    public function hasBindParameters(){
        return 'mysql' === $this->dbms && 0 < count($this->outParams);
    }
}
