<?php

/**
 * @author nowelium
 */
class HermitAnnoteConst extends HermitAnnote {
    
    const TABLE_KEY = 'TABLE';
    const SQL_SUFFIX = '_SQL';
    const QUERY_SUFFIX = '_QUERY';
    const FILE_SUFFIX = '_FILE';
    const PATH_SUFFIX = '_PATH';
    const DELEGATE_SUFFIX = '_DELEGATE';

    protected $reflector;
    protected function __construct(ReflectionClass $reflector){
        $this->reflector = $reflector;
    }
    protected static function createFilePath($pathOfClass, $suffix){
        return dirname($pathOfClass) . '_' . $suffix . '.sql';
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
    public function hasSql($name){
        return $this->reflector->hasConstant($name . self::SQL_SUFFIX);
    }
    public function getSql($name, $instance = null){
        return $this->reflector->getConstant($name . self::SQL_SUFFIX);
    }
    public function hasQuery($name){
        return $this->reflector->hasConstant($name . self::QUERY_SUFFIX);
    }
    public function getQuery($name, $instance = null){
        return $this->reflector->getConstant($name . self::QUERY_SUFFIX);
    }
    public function hasFile($name){
        if(!$this->reflector->hasConstant($name . self::FILE_SUFFIX)){
            return false;
        }
        return file_exists(self::createFilePath($this->reflector->getFileName(), $name));
    }
    public function getFile($name, $instance = null){
        $path = self::createFilePath($this->reflector->getFileName(), $name);
        return file_get_contents($path);
    }
    public function hasPath($name){
        if(!$this->reflector->hasConstant($name . self::PATH_SUFFIX)){
            return false;
        }
        return file_exists($this->getPath($name));
    }
    public function getPath($name, $instance = null){
        return file_get_contents($this->reflector->getConstant($name . self::PATH_SUFFIX));
    }
    public function hasDelegate($name){
        return $this->reflector->hasConstant($name . self::DELEGATE_SUFFIX);
    }
    public function getDelegate($name, $instance = null){
        return $this->reflector->getConstant($name . self::DELEGATE_SUFFIX);
    }
}
