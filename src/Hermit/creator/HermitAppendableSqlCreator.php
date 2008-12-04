<?php

/**
 * @author nowelium
 */
interface HermitAppendableSqlCreator {
    public function addQuery($queryString);
    public function addOrder($orderString);
}
