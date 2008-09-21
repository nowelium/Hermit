<?php

/**
 * @author nowelium
 */
interface HermitFutureProxy extends HermitProxy {
    public static function delegate(ReflectionClass $reflector, $instance = null);
}
