<?php

/**
 * @author nowelium
 */
interface HermitResultSet {
    public function create(HermitStatement $stmt, HermitValueType $type);
}
