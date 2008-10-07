<?php

/**
 * @author nowelium
 *
 */
class HermitUpdateQueryResultSet implements HermitResultSet {
    public function execute(HermitStatement $stmt, HermitValueType $type){
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        unset($stmt);
        return $count;
    }
}