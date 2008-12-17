<?php

/**
 * @author nowelium
 */
final class HermitQueueRecord {
    private $queue;
    private $completed = false;
    public function __construct(HermitQueue $queue){
        $this->queue = $queue;
    }
    public function __destruct(){
        try {
            if($this->completed){
                $this->queue->abort();
            }
        } catch(Exception $e){
            echo $e->getMessage(), PHP_EOL;
            echo $e->getTraceAsString(), PHP_EOL;
        }
    }
    public function complete(){
        $this->completed = true;
        return $this->queue->end();
    }
    public function get(){
        return $this->queue->fetch();
    }
}