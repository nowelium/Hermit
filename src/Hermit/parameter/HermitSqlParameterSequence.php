<?php

/**
 * @author nowelium
 */
class HermitSqlParameterSequence extends HermitSqlParameterHash {
    public function replace($key, $name, $defaultValue){
        return $this->bindKeys[] = '?';
    }
    public function bind(PDOStatement $stmt, $value){
        foreach($this->bindKeys as $index => $key){
            $stmt->bindValue($index, $this->names[$index]);
        }
    }
}
