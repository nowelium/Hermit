<?php

/**
 * @author nowelium
 */
class HermitIteratorResultSet implements Iterator {
    private $statement;
    private $index = 0;
    public function __construct(HermitStatement $stmt){
        $this->statement = $stmt;
    }
    public function __destruct(){
        unset($this->statement);
    }
    public function rewind(){
        if(0 < $this->index){
            throw new RuntimeException('outofbounds');
        }
    }
    public function valid(){
        return false !== $this->current && 1 < $this->statement->columnCount();
    }
    public function current(){
        return $this->current;
    }
    public function key(){
        return $this->index;
    }
    public function next(){
        $this->index++;
        $this->current = $this->statement->fetch();
    }
}
