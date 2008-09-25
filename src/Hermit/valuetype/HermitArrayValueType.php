<?php

/**
 * @author nowelium
 */
class HermitArrayValueType implements HermitValueType {
    public function apply(HermitStatement $stmt){
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    }
}

