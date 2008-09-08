<?php

/**
 * @author nowelium
 */
class HermitFutureProxy {
    protected $pdo;
    protected $target;
    public static function delegate(PDO $pdo, $target){
        $this->pdo = $pdo;
        $this->target = $target;
    }
}
