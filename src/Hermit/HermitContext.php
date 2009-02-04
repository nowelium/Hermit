<?php

/**
 * @author nowelium
 */
class HermitContext {
    protected $reflector;
    protected $batchMode = false;
    public function __construct(ReflectionClass $reflector){
        $this->reflector = $reflector;
    }
    public function getTarget(){
        return $this->target;
    }
    public function getName(){
        return $this->reflector->getName();
    }
    public function getTargetClass(){
        return $this->reflector;
    }
    public function setBatchMode($mode){
        $this->batchMode = $mode;
    }
    public function isBatchMode(){
        return $this->batchMode;
    }
}
