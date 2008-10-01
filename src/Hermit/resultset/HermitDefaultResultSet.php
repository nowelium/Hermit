<?php

/**
 * @author nowelium
 */
class HermitDefaultResultSet implements HermitResultSet {
    public function create(HermitStatement $stmt, HermitValueType $type){
        $type->apply($stmt);
        return $stmt->fetch();
    }
}
