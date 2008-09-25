<?php

/**
 * @author nowelium
 */
class HermitObjectValueType implements HermitValueType {
    public function apply(HermitStatement $stmt){
        $stmt->setFetchMode(PDO::FETCH_OBJ);
    }
}

