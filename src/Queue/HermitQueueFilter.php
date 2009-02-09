<?php

/**
 * @author nowelium
 */
class HermitQueueFilter implements Iterator {
    //
    // FilterIterator だと、fetchしている時に
    // queue::currentの値がずれてしまうため、Iterator で全て実装する
    //
    const QUEUE_FOUND = '1';
    const QUEUE_NOT_FOUND = '0';
    
    protected $queue;
    protected $logger;
    public function __construct(HermitQueueIterator $queue){
        $this->queue = $queue;
        $this->logger = HermitLoggerManager::getLogger();
    }
    public function __destruct(){
        if($this->logger->isDebugEnabled()){
            $this->logger->debug('[%s] queue stoped {%s}', __CLASS__, date('c'));
        }
        unset($this->queue);
    }
    public function rewind(){
        if($this->logger->isDebugEnabled()){
            $this->logger->debug('[%s] queue started {%s}', __CLASS__, date('c'));
        }
        $this->queue->rewind();
    }
    public function key(){
        return $this->queue->key();
    }
    public function current(){
        if($this->logger->isDebugEnabled()){
            $this->logger->debug('[%s] fetch queue %d. {%s}', __CLASS__, $this->key(), date('c'));
        }
        $current = $this->queue->current();
        if(null === $current){
            throw new RuntimeException('[' . __CLASS__ . '] row was null');
        }
        if(0 === strcmp(self::QUEUE_FOUND, $current)){
            return new HermitQueueRecord($this->queue);
        }
        $this->next();
        return $this->current();
    }
    public function next(){
        return $this->queue->next();
    }
    public function valid(){
        return $this->queue->valid();
    }
}