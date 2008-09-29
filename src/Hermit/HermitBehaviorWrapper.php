<?php

/**
 * @author nowelium
 */
interface HermitBehaviorWrapper {
    public function has($targetClass);
    public function createProxy(HermitProxy $proxy, $targetClass);
}
