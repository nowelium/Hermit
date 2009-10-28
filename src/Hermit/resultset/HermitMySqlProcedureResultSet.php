<?php

/**
 * @author nowelium
 */
class HermitMySqlProcedureResultSet extends HermitProcedureResultSet implements HermitParameterBind {
    /**
     * @override
     */
    public function bindParameter(PDO $pdo, array $parameter){
        if(!$this->procParameter->hasBindParameters()){
            return;
        }
        $param = $parameter[0];
        $param->__init__();
        $out = $this->procParameter->getOutParameters();
        $query = 'SELECT ';
        foreach($out as $name){
            $query .= '@' . $name . ',';
        }
        $query = substr($query, 0, -1);
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_NAMED);
        foreach($result as $key => $value){
            // substr(key, 1) => trim @
            $param->set(substr($key, 1), $value);
        }
        $stmt->closeCursor();
        unset($stmt);
    }
}
