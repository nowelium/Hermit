<?php

/**
 * @author nowelium
 */
class HermitMySqlProcedureParameter extends HermitProcedureParameter {
    public function __construct(HermitProcedureInfo $info, $dbms){
        parent::__construct($info, $dbms);
    }

    /**
     * @override
     */
    public function replace($key, $name, $default){
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

    /**
     * @override
     */
    public function bind(PDOStatement $stmt, $value){
        $param = $value[0];
        $param->__init__();
        foreach($this->bindKeys as $index => $key){
            if($this->info->typeofIn($key)){
                $stmt->bindValue(':' . $key, $param->get($key));
            }
        }
    }
}
