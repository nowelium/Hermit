<?php

/**
 * @author nowelium
 */
class HermitResultSet {
    const LIST_SUFFIX = 'List';
    public static function create(HermitStatement $stmt, ReflectionMethod $method){
        $subs = substr($method->getName(), -4);
        if($subs !== false){
            if(strcasecmp($subs, self::LIST_SUFFIX) === 0){
                $results = array();
                foreach($stmt->fetch(PDO::FETCH_OBJ) as $obj){
                    $results[] = $obj;
                }
                return $results;
            }
        }
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
