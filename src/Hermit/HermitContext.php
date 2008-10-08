<?php

/**
 * @author nowelium
 */
class HermitContext {
    protected $targetClass;
    public function __construct($targetClass){
        $this->targetClass = $targetClass;
    }
    public function getTargetClass(){
        return $this->targetClass;
    }
}
