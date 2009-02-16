<?php

/**
 * @author nowelium
 */
class HermitMultiQueueIterator extends HermitQueueIterator {
    public function setTable(array $table){
        $this->table = $table;
    }
    public function getTable(){
        return $this->table;
    }
    public function setTimeout($timeout){
        $this->timeout = $timeout;
    }
    public function getTimeout(){
        return $this->timeout;
    }
    public function wait(){
        $value = array();
        foreach($this->table as $t){
            $value[] = $t;
        }
        $value[] = $this->timeout;
        return $this->hermit->wait($value);
    }
}