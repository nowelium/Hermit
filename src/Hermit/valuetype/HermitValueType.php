<?php

/**
 * @author nowelium
 */
interface HermitValueType {
    public function __construct(HermitAnnote $annote, ReflectionMethod $method, $value);
    public static function accept($value);
    public function apply(HermitStatement $stmt);
}

