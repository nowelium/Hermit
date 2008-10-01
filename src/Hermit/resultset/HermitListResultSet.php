<?php

/**
 * @author nowelium
 *
 */
class HermitListResultSet implements HermitResultSet {
    public function execute(HermitStatement $stmt, HermitValueType $type){
        $type->apply($stmt);
        
        $results = array();
        while($row = $stmt->fetch()){
            $results[] = $row;
        }
        return $results;
    }
}
