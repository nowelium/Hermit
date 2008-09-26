<?php

/**
 * @author nowelium
 */
class HermitDefaultResultSet implements HermitResultSet {
    public function execute(PDOStatement $stmt, HermitValueType $type){
        $type->apply($stmt);
        return $stmt->fetch();
    }
}
