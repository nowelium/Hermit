<?php

/**
 * @author nowelum
 */
class HermitProcedureCallSqlCreator implements HermitSqlCreator {
    private $sql;
    public function initialize(PDO $pdo, ReflectionMethod $method, HermitAnnote $annote){
        $procedureName = $annote->getProcedure($method);
        $dbMeta = HermitDatabaseMetaFactory::get($pdo);
        $info = $dbMeta->getProcedureInfo($procedureName);
        $parameterNames = $info->getParamNames();
        $sql = 'CALL';
        $sql .= ' ';
        $sql .= $procedureName;
        $sql .= '(';
        foreach($parameterNames as $parameterName){
            $sql .= '/*' . $parameterName . '*/';
            $sql .= '"' . $parameterName . '"';
            $sql .= ',';
        }
        $sql = substr($sql, 0, -1);
        $sql .= ')';
        $this->sql = $sql;
    }
    public function createSql(){
        return $this->sql;
    }
}
