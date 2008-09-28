<?php

/**
 * @author nowelium
 */
class HermitArrayValueType extends AbstractHermitValueType {
    const ACCEPT = 'ASSOC';
    public static function accept($value){
        return 0 === strcasecmp($value, self::ACCEPT);
    }
    public function apply(HermitStatement $stmt){
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
    }
}

