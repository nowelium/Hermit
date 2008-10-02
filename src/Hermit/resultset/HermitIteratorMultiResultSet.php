<?php

/**
 * @author nowelium
 */
class HermitIteratorMultiResultSet implements Iterator {
    private $statement;
    private $index = 0;
    private $hasNext = false;
    public function __construct(HermitStatement $stmt){
        $this->statement = $stmt;
    }
    public function __destruct(){
        $this->statement->closeCursor();
        unset($this->statement);
    }
    public function rewind(){
        // nop
    }
    public function valid(){
        return $this->hasNext && 1 < $this->statement->columnCount();
    }
    public function current(){
        $result = array();
        while($row = $this->statement->fetch()){
            $result[] = $row;
        }
        return $result;
    }
    public function key(){
        return $this->index;
    }
    public function next(){
        $this->index++;
        $this->hasNext = $this->statement->nextRowset();
    }
}
