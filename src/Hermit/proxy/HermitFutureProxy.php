<?php

/**
 * @author nowelium
 */
interface HermitFutureProxy extends HermitProxy {
    public static function delegate(PDO $pdo, ReflectionClass $reflector, $instance = null);
}
