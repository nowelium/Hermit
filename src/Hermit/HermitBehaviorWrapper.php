<?php

/**
 * @author nowelium
 */
interface HermitBehaviorWrapper {
    public function has($targetClass);
    public function createProxy(HermitContext $ctx, HermitProxy $proxy);
}
