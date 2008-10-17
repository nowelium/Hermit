<?php

/**
 * @author nowelium
 */
class HermitSqlParameterSequence extends HermitSqlParameterHash {
    public function replace($key, $name, $defaultValue){
        return $this->bindKeys[] = '?';
    }
    public function bind(PDOStatement $stmt, $value){
        foreach($this->names as $name => $pos){
            $stmt->bindValue($pos, $value[$pos]);
        }
    }
}
