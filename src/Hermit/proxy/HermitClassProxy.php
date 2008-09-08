<?php

/**
 * @author nowelium
 */
class HermitClassProxy implements HermitDao {
    protected $target;
    protected $pdo;
    protected function __construct(ReflectionClass $reflector, $targetClass, PDO $pdo = null){
        $this->target = $targetClass;
        $this->reflector = $reflector;
        $this->pdo = $pdo;
    }
    public static function delegate(PDO $pdo, ReflectionClass $reflector, $instance = null){
        return new self($reflector, $instance, $pdo);
    }
    public function __call($name, $params = array()){
        $annote = HermitAnnote::create($this->reflector);
        if($annote->hasMethod($name, true)){
            $method = $annote->getMethod($name);
            return $method->invokeArgs($this->target, $params);
        }
        throw new BadMethodCallException(get_class($this->target) . '::' . $name);
    }
}
