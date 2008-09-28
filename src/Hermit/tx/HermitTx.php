<?php

/**
 * @author nowelium
 */
interface HermitTx {
    public function hasTransaction();
    public function begin();
    public function commit();
    public function rollback();
    public function suspend();
    public function resume(PDO $connection);
    public function proceed(HermitProxy $proxy, $name, array $parameters);
    public function complete(Exception $e);
    public function addCommitRule(Exception $e);
    public function addRollbackRurle(Exception $e);
    public static function create();
}
