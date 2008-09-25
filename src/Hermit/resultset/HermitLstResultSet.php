<?php

/**
 * @author nowelium
 *
 */
class HermitListResultSet implements HermitResultSet {
    public function execute(HermitStatement $stmt, HermitValueType $type){
        $type->apply($stmt);
        
        $results = array();
        while($obj = $stmt->fetch()){
            $results[] = $obj;
        }
        return $results;
    }
}

