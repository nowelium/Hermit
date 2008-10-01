<?php

/**
 * @author nowelium
 *
 */
class HermitListResultSet implements HermitResultSet {
    public function create(HermitStatement $stmt, HermitValueType $type){
        $type->apply($stmt);
        
        $results = array();
        while($row = $stmt->fetch()){
            $results[] = $row;
        }
        return $results;
    }
}
