<?php

/**
 * @author nowelium
 */
class HermitQ extends Hermit implements Iterator {
    const DEFAULT_QUEUE_TIMEOUT = 10;
    protected $queue;
    protected $iterator;
    protected $delegate;
    public function __construct($targetClass, $timeout = self::DEFAULT_QUEUE_TIMEOUT){
        $hermit = new parent($targetClass);
        $it = new HermitQueueIterator($hermit);
        $reflector = $hermit->context->getTargetClass();
        if(!$reflector->hasConstant('TABLE')){
            throw new RuntimeException($hermit->context->getName() . ' has not constant "TABLE"');
        }
        $it->setTable($reflector->getConstant('TABLE'));
        $it->setTimeout($timeout);
        $this->queue = $hermit;
        $this->iterator = $it;
        $this->filter = new HermitQueueFilter($it);
    }
    public function rewind(){
        return $this->filter->rewind();
    }
    public function key(){
        return $this->filter->key();
    }
    public function current(){
        return $this->filter->current();
    }
    public function next(){
        return $this->filter->next();
    }
    public function valid(){
        return $this->filter->valid();
    }
}