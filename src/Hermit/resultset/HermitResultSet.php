<?php

/**
 * @author nowelium
 */
interface HermitResultSet {
    public function execute(HermitStatement $stmt, HermitValueType $type);
}

