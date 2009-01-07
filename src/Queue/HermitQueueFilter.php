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
    public function __construct(HermitQueueIterator $queue){
        $this->queue = $queue;
    }
    public function rewind(){
        $this->queue->rewind();
    }
    public function key(){
        return $this->queue->key();
    }
    public function current(){
        $current = $this->queue->current();
        if(null === $current){
            throw new RuntimeException('fetch queue row was null');
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