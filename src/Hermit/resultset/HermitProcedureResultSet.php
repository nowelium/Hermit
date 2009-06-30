<?php

/**
 * @author nowelium
 */
class HermitProcedureResultSet implements HermitResultSet {
    /**
     * @var ReflectionMethod
     */
    protected $method;
    /**
     * @var HermitAnnote
     */
    protected $annote;
    /**
     * @var HermitProcedureParameter
     */
    protected $procParameter;
    public function __construct(ReflectionMethod $method, HermitAnnote $annote, HermitProcedureParameter $procParameter){
        $this->method = $method;
        $this->annote = $annote;
        $this->procParameter = $procParameter;
    }
    public function execute(HermitStatement $stmt, HermitValueType $type){
        if($stmt->columnCount() < 1){
            $stmt->closeCursor();
            unset($stmt);
            return null;
        }
        
        //
        // check single result
        //
        if($this->annote->isSingleProcedureResult($this->method)){
            $resultset = HermitResultSetFactory::create($this->method);
            return $resultset->execute($stmt, $type);
        }

        $resultset = HermitResultSetFactory::create($this->method);
        $results = array();
        do {
            $results[] = $resultset->execute($stmt, $type);
        } while($stmt->nextRowset());
        
        $stmt->closeCursor();
        unset($stmt);
        return $results;
    }
}
