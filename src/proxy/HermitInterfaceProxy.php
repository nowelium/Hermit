<?php

/**
 * @author nowelium
 */
class HermitInterfaceProxy {
    protected $pdo;
    protected $reflector;
    protected function __construct(ReflectionClass $reflector, $pdo){
        $this->reflector = $reflector;
        $this->pdo = $pdo;
    }
    public static function delegate(PDO $pdo, ReflectionClass $reflector){
        return new self($reflector, $pdo);
    }
    public function __call($name, $params = array()){
        $annote = HermitAnnote::create($this->reflector);
        if(!$annote->hasMethod($name)){
            throw new BadMethodCallException($this->reflector->getName() . '::' . $name);
        }

        $method = $annote->getMethod($name);
        switch(true){
        case $annote->hasSql($name):
            return $this->execute($method, $annote->getSql($name));
        case $annote->hasQuery($name):
            $sql = 'SELECT * FROM ' . $annote->getTable() . ' WHERE ' . $annote->getQuery($name);
            return $this->execute($method, $sql);
        case $annote->hasFile($name):
            return $this->execute($method, $annote->getFile($name));
        case $annote->hasPath($name):
            return $this->execute($method, $annote->getPath($name));
        case $annote->hasDelegate($name);
            break;
        }
        throw new BadMethodCallException($this->reflector->getName() . '::' . $name);
    }
    protected static function execute(ReflectionMethod $method, $sql){
        $stmt = HermitSqlBuilder::prepare($this->pdo, $method, $sql);
        $stmt->execute($params);
        return HermitResultSet::create($stmt, $method);
    }
}
