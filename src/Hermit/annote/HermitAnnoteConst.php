<?php

/**
 * @author nowelium
 */
class HermitAnnoteConst extends HermitAnnote {
    
    const TABLE_KEY = 'TABLE';
    const SQL_SUFFIX = '_SQL';
    const QUERY_SUFFIX = '_QUERY';
    const FILE_SUFFIX = '_FILE';
    const PROCEDURE_SUFFIX = '_PROCEDURE';
    const VALUE_TYPE_SUFFIX = '_VALUE_TYPE';

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
        $key = $method->getName() . self::PROCEDURE_SUFFIX;
        if($this->reflector->hasConstant($key)){
            return $this->reflector->getConstant($key);
        }
        return null;
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
        $key = $method->getName() . self::QUERY_SUFFIX;
        if($this->reflector->hasConstant($key)){
            return $this->reflector->getConstant($key);
        }
        return null;
    }
    public function getFile(ReflectionMethod $method){
        $key = $method->getName() . self::FILE_SUFFIX;
        if($this->reflector->hasConstant($key)){
            $path = $this->reflector->getConstant($key);
            if(file_exists($path)){
                return file_get_contents($path);
            }
        }
        return null;
    }
    public function getValueType(ReflectionMethod $method){
        $key = $method->getName() . self::VALUE_TYPE_SUFFIX;
        if($this->reflector->hasConstant($key)){
            return $this->reflector->getConstant($key);
        }
        return null;
    }
}
