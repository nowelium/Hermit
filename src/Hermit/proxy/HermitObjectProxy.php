<?php

/**
 * @author nowelium
 */
class HermitObjectProxy implements HermitFutureProxy {
    protected $pdo;
    protected $target;
    protected $annote;
    protected function __construct(PDO $pdo, ReflectionClass $reflector, $target){
        $this->pdo = $pdo;
        $this->target = $target;
        $this->annote = HermitAnnote::create($reflector);
    }
    public static function delegate(PDO $pdo, ReflectionClass $reflector, $instance = null){
        return new self($pdo, $reflector, $instance);
    }
    public function request($name, array $params){
        if($this->annote->hasMethod($name, true)){
            $method = $this->annote->getMethod($name);
            return $method->invokeArgs($this->target, $params);
        }
        throw new BadMethodCallException(get_class($this->target) . '::' . $name);
    }
}
