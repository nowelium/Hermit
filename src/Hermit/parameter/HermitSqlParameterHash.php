<?php

/**
 * @author nowelium
 */
class HermitSqlParameterHash extends HermitSqlParameter {
    protected $names = array();
    protected $bindKeys = array();
    public function add($name, $index){
        $this->names[$name] = $index;
    }
    protected function hasParameter($name){
        return isset($this->names[$name]);
    }
    public function replace($key, $name, $defaultValue){
        $this->bindKeys[] = $name;
        return ':' . $name;
    }
    public function bind(PDOStatement $stmt, $value){
        $logger = HermitLoggerManager::getLogger();
        if($logger->isDebugEnabled()){
            $buf = '';
            foreach($this->names as $name => $pos){
                $buf .= ':' . $name . ' => ' . $value[$pos];
            }
            $logger->debug('{%s} statement binds parameter {:key => param} = %s', __CLASS__, $buf);
        }
        
        foreach($this->names as $name => $pos){
            $stmt->bindValue(':' . $name, $value[$pos]);
        }
    }
    
    public function monoCreate($expression, $statement, $parameterValue){
        throw new RuntimeException('T.B.D');
    }
    public function binoCreate($expression, $trueStatement, $falseStatement, $parameterValue){
        foreach($this->names as $name => $pos){
            if(strpos($expression, $name) !== false){
                $value = $parameterValue[$pos];
                if(is_string($value)){
                    $value = '\'' . $value . '\'';
                }
                
                $expression = strtr($expression, array($name => $value));
                if(eval('return ' . $expression . ';')){
                    $expression = $trueStatement;
                } else {
                    $expression = $falseStatement;
                }
            }
        }
        return $expression;
    }
}
