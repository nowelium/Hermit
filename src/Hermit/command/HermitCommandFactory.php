<?php

/**
 * @author nowelium
 */
interface HermitCommandFactory {
    public function setAnnote(HermitAnnote $annote);
    public function setMethod(ReflectionMethod $method);
    public function create(PDO $pdo, HermitContext $context);
}