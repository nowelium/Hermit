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
        foreach($out as $name){
            $stmt = $pdo->prepare('SELECT @' . $name);
            $stmt->execute();

            $param->set($name, $stmt->fetchColumn());
            $stmt->closeCursor();
            unset($stmt);
        }
    }
}
