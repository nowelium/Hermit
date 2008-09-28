<?php

/**
 * @author nowelium
 */
class HermitDefaultResultSet implements HermitResultSet {
    public function execute(HermitStatement $stmt, HermitValueType $type){
        $type->apply($stmt);
        return $stmt->fetch();
    }
}

