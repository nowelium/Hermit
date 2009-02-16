<?php

/**
 * @author nowelium
 */
class HermitQueueIndexedRecord extends HermitQueueRecord {
    protected $index;
    protected $dao;
    public function __construct($index, Hermit $dao, HermitQueue $queue){
        parent::__construct($queue);
        $this->index = $index;
        $this->dao = $dao;
    }
    public function getIndex(){
        return $this->index;
    }
    public function get(){
        return $this->dao->get();
    }
}