<?php

/**
 * @author nowelium
 */
class HermitNopValueType extends AbstractHermitValueType {
    public static function accept($value){
        return true;
    }
    public function apply(HermitStatement $stmt){
        // nop
    }
}
