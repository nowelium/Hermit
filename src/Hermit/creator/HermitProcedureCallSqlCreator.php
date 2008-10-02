<?php

/**
 * @author nowelum
 */
class HermitProcedureCallSqlCreator implements HermitSqlCreator, HermiSetupSqlCreator {
    private $sql;
    private $setupSql;
    public function initialize(PDO $pdo, ReflectionMethod $method, HermitAnnote $annote){
        $procedureName = $annote->getProcedure($method);
        $dbMeta = HermitDatabaseMetaFactory::get($pdo);
        $info = $dbMeta->getProcedureInfo($procedureName);

        $this->setupSql = self::generateSetupSql($info);
        $this->sql = self::generateCallSql($info);
    }
    public function createSql(){
        return $this->sql;
    }
    public function createSetupSql(){
        return $this->setupSql;
    }
    public function hasSetupSql(){
        return null !== $this->setupSql;
    }
    protected static function generateCallSql(HermitProcedureInfo $info){
        $parameterNames = $info->getParamNames();
        $callSql = 'CALL';
        $callSql .= ' ';
        $callSql .= $info->getName();
        $callSql .= '(';
        foreach($parameterNames as $parameterName){
            $callSql .= '/*' . $parameterName . '*/';
            $callSql .= '"' . $parameterName . '"';
            $callSql .= ',';
        }
        $callSql = substr($callSql, 0, -1);
        $callSql .= ')';
        return $callSql;
    }
    protected static function generateSetupSql(HermitProcedureInfo $info){
        $begin = false;
        $parameterNames = $info->getParamNames();

        $setupSql = 'SET';
        $setupSql .= ' ';
        foreach($parameterNames as $parameterName){
            if($info->typeofIn($parameterName)){
               continue;
            }
            $begin = true;
            $setupSql .= '@' . $parameterName;
            $setupSql .= ' = ';
            $setupSql .= '/*' . $parameterName . '*/';
            $setupSql .= '"' . $parameterName . '"';
            $setupSql .= ',';
        }
        if(!$begin){
            return null;
        }
        return substr($setupSql, 0, -1);
    }
}
