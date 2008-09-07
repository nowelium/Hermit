<?php

/**
 * @authro nowelium
 */
abstract class AbstractHermitDao implements HermitDao {
    protected $pdo;
    public function setPDO(PDO $pdo){
        $this->pdo = $pdo;
    }
}
