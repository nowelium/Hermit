<?php

/**
 * @author nowelium
 */
interface HermitFutureProxy extends HermitProxy {
    public static function delegate(HermitContext $ctx, ReflectionClass $reflector, $instance = null);
}
