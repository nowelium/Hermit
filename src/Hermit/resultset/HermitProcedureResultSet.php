<?php

/**
 * @author nowelium
 */
class HermitProcedureResultSet implements HermitResultSet {
    protected $procParameter;
    public function __construct(HermitProcedureParameter $procParameter){
        $this->procParameter = $procParameter;
    }
    public function execute(HermitStatement $stmt, HermitValueType $type){
        if($stmt->columnCount() < 1){
            $stmt->closeCursor();
            unset($stmt);
            return null;
        }
        $type->apply($stmt);

//        $rows = array();
//        while($row = $stmt->fetch()){
//            $rows[] = $row;
//        }
//        if(!$stmt->nextRowset()){
//            return $rows;
//        }
//
//        $results = array();
//        $results[] = $rows;
//        do {
//            $rows = array();
//            while($row = $stmt->fetch()){
//                $rows[] = $row;
//            }
//            $results[] = $rows;
//        } while($stmt->nextRowset());
//
//        $stmt->closeCursor();
//        unset($stmt);
//        return $results;

        // multiresult so always to array[array]
        $results = array();
        do {
            $rows = array();
            while($row = $stmt->fetch()){
                $rows[] = $row;
            }
            $results[] = $rows;
        } while($stmt->nextRowset());
        
        $stmt->closeCursor();
        unset($stmt);
        return $results;
    }
}
