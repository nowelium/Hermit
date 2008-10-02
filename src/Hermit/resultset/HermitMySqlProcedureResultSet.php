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
