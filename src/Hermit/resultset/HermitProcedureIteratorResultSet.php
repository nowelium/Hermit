<?php

/**
 * @author nowelium
 */
class HermitProcedureIteratorResultSet implements HermitResultSet {
    public function execute(HermitResultSet $stmt, HermitValueType $type){
        $type->apply($stmt);
        return new HermitIteratorMultiResultSet($stmt);
    }
}
