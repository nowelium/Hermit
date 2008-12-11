<?php

/**
 * @author nowelium
 */
class HermitAnnoteConst extends HermitAnnote {
    
    const TABLE_KEY = 'TABLE';
    const COLUMNS_KEY = 'COLUMNS';
    const SQL_SUFFIX = '_SQL';
    const QUERY_SUFFIX = '_QUERY';
    const ORDER_SUFFIX = '_ORDER';
    const FILE_SUFFIX = '_FILE';
    const PROCEDURE_SUFFIX = '_PROCEDURE';
    const VALUE_TYPE_SUFFIX = '_VALUE_TYPE';
    const CHECK_SINGLE_ROW_UPDATE = 'CHECK_SINGLE_ROW_UPDATE';
    
    const DEFAULT_PREFIX = 'default';

    const UNDERSCORE = '_';
    const SQL_FILE_EXTENSION = '.sql';

    protected $reflector;
    public function __construct(ReflectionClass $reflector){
        $this->reflector = $reflector;
    }
    protected static function underscore(){
        $args = func_get_args();
        $buf = '';
        foreach($args as $arg){
            $buf .= $arg;
            $buf .= self::UNDERSCORE;
        }
        return substr($buf, 0, -1);
    }
    protected static function getMethodAnnotation(ReflectionClass $reflector, ReflectionMethod $method, $suffix){
        $key = $method->getName() . $suffix;
        if($reflector->hasConstant($key)){
            return $reflector->getConstant($key);
        }
        return null;
    }
    protected static function getClassAnnotation(ReflectionClass $reflector, $name){
        if($reflector->hasConstant($name)){
            return $reflector->getConstant($name);
        }
        return null;
    }
    
    public function getTable(){
        return $this->reflector->getConstant(self::TABLE_KEY);
    }
    public function hasMethod($name, $checkAbstract = false){
        if(!$this->reflector->hasMethod($name)){
            return false;
        }
        $method = $this->getMethod($name);
        if(!$method->isPublic()){
            return false;
        }
        if($checkAbstract){
            return !$method->isAbstract();
        }
        return true;
    }
    public function getMethod($name){
        return $this->reflector->getMethod($name);
    }
    public function getProcedure(ReflectionMethod $method){
        return self::getMethodAnnotation($this->reflector, $method, self::PROCEDURE_SUFFIX);
    }
    public function getSql(ReflectionMethod $method, $suffix = null){
        if(is_null($suffix)){
            $suffix = '';
        }
        $methodName = $method->getName();
        $key1 = $methodName . self::UNDERSCORE . $suffix . self::SQL_SUFFIX;
        if($this->reflector->hasConstant($key1)){
            return $this->reflector->getConstant($key1);
        }
        $key2 = $methodName . self::SQL_SUFFIX;
        if($this->reflector->hasConstant($key2)){
            return $this->reflector->getConstant($key2);
        }

        $fileName = $this->reflector->getFileName();
        $className = $this->reflector->getName();
        $dirPath = dirname($fileName) . DIRECTORY_SEPARATOR;
        $key3 = $dirPath . self::underscore($className, $methodName, $suffix) . self::SQL_FILE_EXTENSION;
        if(file_exists($key3)){
            return file_get_contents($key3);
        }
        $key4 = $dirPath . self::underscore($className, $methodName) . self::SQL_FILE_EXTENSION;
        if(file_exists($key4)){
            return file_get_contents($key4);
        }
        return null;
    }
    public function getQuery(ReflectionMethod $method){
        return self::getMethodAnnotation($this->reflector, $method, self::QUERY_SUFFIX);
    }
    public function getOrder(ReflectionMethod $method){
        return self::getMethodAnnotation($this->reflector, $method, self::ORDER_SUFFIX);
    }
    
    public function getFile(ReflectionMethod $method){
        $path = self::getMethodAnnotation($this->reflector, $method, self::FILE_SUFFIX);
        if($path !== null){
            if(file_exists($path)){
                return file_get_contents($path);
            }
        }
        return null;
    }
    public function getValueType(ReflectionMethod $method){
        $valueType = self::getMethodAnnotation($this->reflector, $method, self::VALUE_TYPE_SUFFIX);
        if(null === $valueType){
            return self::getClassAnnotation($this->reflector, self::DEFAULT_PREFIX . self::VALUE_TYPE_SUFFIX);
        }
        return $valueType;
    }
    public function getColumns(){
        $columns = $this->reflector->getConstant(self::COLUMNS_KEY);
        return array_map('trim', explode(',', $columns));
    }
}
