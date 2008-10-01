<?php

/**
 * @author nowelium
 */
class HermitProcedureResultSet implements HermitResultSet, HermitParameterBind {
    private $procParameter;
    public function __construct(HermitProcedureParameter $procParameter){
        $this->procParameter = $procParameter;
    }
    public function create(HermitStatement $stmt, HermitValueType $type){
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
        $param = $parameter[0];
        $dbms = HermitDatabaseMetaFactory::getDbms($pdo);
        if('mysql' === $dbms){
            if(!$this->procParameter->hasBindParameters()){
                return;
            }
            $out = $this->procParameter->getOutParameters();
            foreach($out as $name){
                $stmt = $pdo->prepare('SELECT @' . $name);
                $stmt->bindColumn(1, $param->$name);
                $stmt->execute();
                
                $stmt->fetch(PDO::FETCH_BOUND);
                $stmt->closeCursor();
                unset($stmt);
            }
        }
    }
}
