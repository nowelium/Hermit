<?php

/**
 * @author nowelium
 */
class HermitQ extends Hermit implements Iterator {
    const DEFAULT_QUEUE_TIMEOUT = 10;
    protected $queue;
    protected $iterator;
    protected $filter;
    protected $logger;
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
        $this->logger = HermitLoggerManager::getLogger();
        
        register_shutdown_function(array($this, '__destruct'));
    }
    public function __destruct(){
        unset($this->filter);
        unset($this->iterator);
        unset($this->queue);
        if($this->logger->isDebugEnabled()){
            $this->logger->debug('[%s] has stoped {%s}', __CLASS__, date('c'));
        }
    }
    
    public function getFilter(){
        return $this->filter;
    }
    public function getIterator(){
        return $this->iterator;
    }

    public function rewind(){
        if($this->logger->isDebugEnabled()){
            $this->logger->debug('[%s] has started {%s}', __CLASS__, date('c'));
        }
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