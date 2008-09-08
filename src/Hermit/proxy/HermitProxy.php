<?php

/**
 * @author nowelium
 */
interface HermitProxy extends HermitDao {
    public static function delegate(PDO $pdo, ReflectionClass $reflector, $instance = null);
}
