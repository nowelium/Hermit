<?php

/**
 * @author nowelium
 */
class HermitProcedureResultSet implements HermitResultSet, HermitParameterBind {
    protected $procParameter;
    public function __construct(HermitProcedureParameter $procParameter){
        $this->procParameter = $procParameter;
    }
    public function execute(HermitStatement $stmt, HermitValueType $type){
        $type->apply($stmt);

        if($stmt->columnCount() < 1){
            return null;
        }

        $results = array();
        do {
            $row = $stmt->fetch();
            if(false === $row){
                break;
            }
            $results[] = $row;
        } while($stmt->nextRowset());
        return $results;
    }
    public function bindParameter(PDO $pdo, array $parameter){
    }
}
