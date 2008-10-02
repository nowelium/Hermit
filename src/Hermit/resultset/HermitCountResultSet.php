<?php

/**
 * @author nowelium
 */
class HermitCountResultSet implements HermitResultSet {
    public function execute(HermitStatement $stmt, HermitValueType $type){
        if($row = $stmt->fetch(PDO::FETCH_NUM)){
            // first column only
            $count = (int) $row[0];
            return $count;
        }
        return -1;
    }
}
