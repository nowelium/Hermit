<?php

/**
 * @author nowelium
 */
class HermitQueueIterator implements HermitQueue, Iterator {
    protected $hermit;
    protected $table;
    protected $timeout = 0;
    protected $index = 0;
    public function __construct(Hermit $hermit){
        $this->hermit = $hermit;
    }
    public function setTable($table){
        $this->table = $table;
    }
    public function setTimeout($timeout){
        $this->timeout = $timeout;
    }
    public function fetch(){
        return $this->hermit->get();
    }
    public function wait(){
        return $this->hermit->wait($this->table, $this->timeout);
    }
    public function end(){
        return $this->hermit->end($this->table);
    }
    public function abort(){
        return $this->hermit->abort();
    }
    public function enqueue(){
        $param = new HermitParam;
        $row = $this->fetch();
        foreach($row as $property => $value){
            $param->set($property, $value);
        }
        return $this->hermit->add($param);
    }
    public function rewind(){
        $this->index = 0;
    }
    public function key(){
        return $this->index;
    }
    public function current(){
        $row = $this->wait();
        return $row[0];
    }
    public function next(){
        return $this->index++;
    }
    public function valid(){
        //
        // while true
        //
        return true;
    }
}