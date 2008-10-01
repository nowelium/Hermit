<?php

/**
 * @author nowelium
 */
interface HermitParameterBind {
    public function bindParameter(PDO $pdo, array $parameter);
}
