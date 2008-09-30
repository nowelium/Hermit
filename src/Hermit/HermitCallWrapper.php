<?php

/**
 * @author nowelium
 */
interface HermtCallWrapper {
    public function has($methodName);
    public function execute(HermitProxy $proxy, $methodName, array $parameters);
}