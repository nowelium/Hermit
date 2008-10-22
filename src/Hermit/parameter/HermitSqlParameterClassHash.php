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
                        $method = $this->reflector->getMethod('get' . ucfirst($key));
                        $v = $method->invoke($obj);
                    }
                    $buf .= $key . ' => ' . $v;
                }
            }
            $logger->debug('statement binds parameter {:key => param} = %s', $buf);
        }
        
        
        foreach($value as $obj){
            foreach($this->bindKeys as $key){
                if($obj instanceof HermitParam){
                    $stmt->bindValue(':' . $key, $obj->get($key));
                } else {
                    $method = $this->reflector->getMethod('get' . ucfirst($key));
                    $stmt->bindValue(':' . $key, $method->invoke($obj));
                }
            }
        }
    }
}
