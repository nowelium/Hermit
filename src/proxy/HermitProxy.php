<?php

/**
 * @author nowelium
 */
class HermitProxy {
    protected $target;
    protected $pdo;
    protected function __construct(ReflectionClass $reflector, $targetClass, PDO $pdo = null){
        $this->target = $targetClass;
        $this->reflector = $reflector;
        $this->pdo = $pdo;
    }
    public static function delegateInstance(PDO $pdo, $instance){
        return new self(new ReflectionObject($reflector), $instance, $pdo);
    }
    public function __call($name, $params = array()){
        $annote = HermitAnnote::create($this->reflector);
        if($annote->hasMethod($name, true)){
            $method = $annote->getMethod($name);
            return $method->invokeArgs($this->target, $params);
        }
        switch(true){
        case $annote->hasSql($name):
            break;
        case $annote->hasQuery($name):
            break;
        case $annote->hasFile($name):
            break;
        case $annote->hasPath($name):
            break;
        case $annote->hasDelegate($name):
            break;
        }
        throw new BadMethodCallException(get_class($this->target) . '::' . $name);
    }
}
