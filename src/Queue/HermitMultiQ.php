<?php

/**
 * @author nowelium
 */
class HermitMultiQ extends Hermit implements Iterator {
    const DEFAULT_QUEUE_TIMEOUT = 10;
    protected $iterator;
    protected $filter;
    public function __construct(array $targetDaoList, $timeout = self::DEFAULT_QUEUE_TIMEOUT){
        $tables = array();
        $list = array();
        foreach($targetDaoList as $daoName){
            $hermit = new Hermit($daoName);
            $reflector = $hermit->context->getTargetClass();
            if(!$reflector->hasConstant('TABLE')){
                throw new RuntimeException($hermit->context->getName() . ' has not constant "TABLE"');
            }
            $tables[] = $reflector->getConstant('TABLE');
            $list[] = $hermit;
        }
        $iterator = new HermitMultiQueueIterator(new Hermit('HermitQueueMultiDao'));
        $iterator->setTable($tables);
        $iterator->setTimeout($timeout);
        
        $filter = new HermitMultiQueueFilter;
        $filter->setHermits($list);
        
        $this->iterator = $iterator;
        $this->filter = new HermitMultiQueueFilter($iterator);
    }
    public function __destruct(){
        unset($this->filter);
        unset($this->iterator);
    }
    
    public function setReleaseConnection(array $callback){
        $this->filter->releaseConnection($callback);
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