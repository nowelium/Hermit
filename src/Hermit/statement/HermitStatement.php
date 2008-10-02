<?php

/**
 * @author nowelium
 */
interface HermitStatement {
    public function getSqlParameter();
    public function execute($parameterValue = array());
    public function __call($name, $params);
}
