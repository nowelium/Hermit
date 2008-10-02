<?php

/**
 * @author nowelium
 */
class HermitNumberValueType extends AbstractHermitValueType {
    const ACCEPT = 'NUM';
    public static function accept($value){
        return 0 === strcasecmp($value, self::ACCEPT);
    }
    public function apply(HermitStatement $stmt){
        $stmt->setFetchMode(PDO::FETCH_NUM);
    }
}
