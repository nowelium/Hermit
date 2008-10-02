<?php

/**
 * @author nowelium
 */
interface HermiSetupSqlCreator {
    public function createSetupSql();
    public function hasSetupSql();
}