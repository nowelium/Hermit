<?php

/**
 * @author nowelium
 */
class HermitSqlParameterHash extends HermitSqlParameter {
    
    const PROPERTY_SEPARATOR = ' ';
    
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
        foreach($this->names as $name => $pos){
            if(!isset($parameterValue[$pos])){
                continue;
            }
            $value = $parameterValue[$pos];
            if(is_object($value)){
                // value was dto
                $r = new ReflectionClass($value);
                $props = $r->getProperties();
                foreach($props as $index => $property){
                    $propertyName = $property->getName();
                    if(false === strpos($expression, $propertyName . self::PROPERTY_SEPARATOR)){
                        continue;
                    }
                    
                    $namedValue = self::makeExpression($property->getValue($value));
                    $expression = strtr($expression, array(
                        $propertyName . self::PROPERTY_SEPARATOR => $namedValue . self::PROPERTY_SEPARATOR
                    ));
                    if(eval('return ' . $expression . ';')){
                        return self::MONO_MATCHED;
                    }
                }
            } else {
                if(false === strpos($expression, $name)){
                    continue;
                }
                $value = self::makeExpression($value);
                $expression = strtr($expression, array($name => $value));
                if(eval('return ' . $expression . ';')){
                    return self::MONO_MATCHED;
                }
            }
        }
        return self::MONO_UNMATCH;
    }
    public function binoCreate($expression, $trueStatement, $falseStatement, $parameterValue){
        foreach($this->names as $name => $pos){
            if(!isset($parameterValue[$pos])){
                continue;
            }
            $value = $parameterValue[$pos];
            if(is_object($value)){
                // value was dto
                $r = new ReflectionClass($value);
                $props = $r->getProperties();
                foreach($props as $property){
                    $propertyName = $property->getName();
                    if(false === strpos($expression, $propertyName . self::PROPERTY_SEPARATOR)){
                        continue;
                    }
                    $namesValue = self::makeExpression($value->$propertyName);
                    $expression = strtr($expression, array(
                        $propertyName . self::PROPERTY_SEPARATOR => $namesValue . self::PROPERTY_SEPARATOR
                    ));
                    if(eval('return ' . $expression . ';')){
                        $expression = $statement;
                    }
                }
            } else {
                if(false === strpos($expression, $name)){
                    continue;
                }
                $value = self::makeExpression($parameterValue[$pos]);
                $expression = strtr($expression, array($name => $value));
                if(eval('return ' . $expression . ';')){
                    return self::BINO_TRUE_MATCHED;
                }
                return self::BINO_FALSE_MATCHED;
            }
        }
        return self::BINO_UNMATCH;
    }
    
    protected static function makeExpression($value){
        if(null === $value){
            return 'null';
        }
        if(is_string($value)){
            return '\'' . $value . '\'';
        }
        if(is_bool($value)){
            if(true === $value){
                return 'true';
            }
            return 'false';
        }
        if(is_array($value)){
            return var_export($value, true);
        }
        return $value;
    }
}
