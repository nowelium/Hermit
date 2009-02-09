<?php

/**
 * @author nowelium
 */
abstract class AbstractHermitSqlCommand implements HermitSqlCommand {
    protected $context;
    protected $method;
    protected $sqlCreator;
    protected $type;
    protected $statement;
    public function setContext(HermitContext $ctx){
        $this->context = $ctx;
    }
    public function setMethod(ReflectionMethod $method){
        $this->method = $method;
    }
    public function setSqlCreator(HermitSqlCreator $sqlCreator){
        $this->sqlCreator = $sqlCreator;
    }
    public function setValueType(HermitValueType $type){
        $this->type = $type;
    }
    protected function getConnection($event = HermitEvent::UNKNOWN){
        return HermitDataSourceManager::get(
            $this->context->getName(),
            $this->method->getName(),
            $event
        );
    }
    protected function buildStatement($event = HermitEvent::UNKNOWN, array $parameters){
        $genStmt = false;
        if(null === $this->statement){
            $genStmt = true;
        } else if(!$this->context->isBatchMode()){
            $genStmt = true;
        }
        
        if($genStmt){
            $pdo = $this->getConnection($event);
            $builder = new HermitStatementBuilder($this->context->getTargetClass(), $this->method, $this->sqlCreator);
            $this->statement = $builder->build($pdo, $parameters);
        }
        return $this->statement;
    }
}