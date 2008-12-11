<?php

/**
 * @author nowelium
 */
abstract class AbstractHermitSqlCommand implements HermitSqlCommand {
    protected $context;
    protected $method;
    protected $sqlCreator;
    protected $type;
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
}