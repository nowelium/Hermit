<?php

/**
 * @author nowelium
 */
class HermitSetupStatementBuilder extends HermitProcedureStatementBuilder {
    public function __construct(ReflectionMethod $method, HermitAnnote $annote, HermitSqlCreator $sqlCreator){
        parent::__construct($method, $annote, $sqlCreator);
    }
    public function build(PDO $pdo){
        parent::checkProcedureParameter($this->method);

        $procedureName = $this->annote->getProcedure($this->method);
        $meta = HermitDatabaseMetaFactory::get($pdo);
        $info = $meta->getProcedureInfo($procedureName);

        $dbms = HermitDatabaseMetaFactory::getDbms($pdo);
        $parameter = new HermitProcedureParameter($info, $dbms);
        
        $setupSql = self::preparedSql($parameter, $this->sqlCreator->createSetupSql());
        return new HermitDefaultStatement($parameter, $pdo->prepare($setupSql));
    }
}
