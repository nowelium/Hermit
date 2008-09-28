<?php

/**
 * @author nowelium
 */
class HermitObjectValueType extends AbstractHermitValueType {
    const ACCEPT = 'OBJ';
    public static function accept($value){
        return 0 === strcasecmp($value, self::ACCEPT);
    }
    public function apply(HermitStatement $stmt){
        $stmt->setFetchMode(PDO::FETCH_OBJ);
    }
}

