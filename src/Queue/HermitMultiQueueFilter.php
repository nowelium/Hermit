<?php

/**
 * @author nowelium
 */
class HermitMultiQueueFilter extends HermitQueueFilter {
    protected $hermits = array();
    
    public function setHermits(array $hermits){
        $this->hermits = $hermits;
    }
    
    public function current(){
        while(true){
            if($this->logger->isDebugEnabled()){
                $this->logger->debug('[%s] fetch queue %d. {%s}', __CLASS__, $this->key(), date('c'));
            }
            
            $tableIndex = $this->queue->current();
            if(null === $tableIndex){
                throw new RuntimeException('[' . __CLASS__ . '] row was null');
            }
            if(0 < $tableIndex){
                $index = $tableIndex - 1;
                $hermit = $this->hermits[$index];
                return new HermitQueueIndexedRecord($index, $hermit, $this->queue);
            }
            $this->next();
        }
    }
}