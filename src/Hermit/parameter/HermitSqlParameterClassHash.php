<?php

/**
 * @author nowelium
 */
class HermitSqlParameterClassHash extends HermitSqlParameterHash {
    private $reflector;
    public function __construct(ReflectionClass $reflector){
        $this->reflector = $reflector;
    }
    public function bind(PDOStatement $stmt, $value){
        $logger = HermitLoggerManager::getLogger();
        if($logger->isDebugEnabled()){
            $buf = '';
            foreach($value as $obj){
                foreach($this->bindKeys as $key){
                    $v = null;
                    if($obj instanceof HermitParam){
                        $v = $obj->get($key);
                    } else {
                        $methodName = 'get' . ucfirst($key);
                        if($this->reflector->hasMethod($methodName)){
                            $method = $this->reflector->getMethod($methodName);
                            $v = $method->invoke($obj);
                        } else {
                            $v = $obj->$key;
                        }
                    }
                    $buf .= $key . ' => ' . $v;
                }
            }
            $logger->debug('{%s} statement binds parameter {:key => param} = %s', __CLASS__, $buf);
        }
        
        foreach($value as $obj){
            foreach($this->bindKeys as $key){
                if($obj instanceof HermitParam){
                    $stmt->bindValue(':' . $key, $obj->get($key));
                } else {
                    $methodName = 'get' . ucfirst($key);
                    if($this->reflector->hasMethod($methodName)){
                        $method = $this->reflector->getMethod($methodName);
                        $stmt->bindValue(':' . $key, $method->invoke($obj));
                    } else {
                        $stmt->bindValue(':' . $key, $obj->$key);
                    }
                }
            }
        }
    }
}
