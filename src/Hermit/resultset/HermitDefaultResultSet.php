<?php

/**
 * @author nowelium
 */
class HermitDefaultResultSet implements HermitResultSet {
    public function execute(HermitStatement $stmt, HermitValueType $type){
        $type->apply($stmt);
        if($row = $stmt->fetch()){
            $stmt->closeCursor();
            return $row;
        }
        return null;
    }
}
