<?php

/**
 * @author nowelium
 */
class HermitSetupStatementBuilder extends HermitProcedureStatementBuilder {
    public function __construct(ReflectionMethod $method, HermitAnnote $annote, HermitSqlCreator $sqlCreator){
        parent::__construct($method, $annote, $sqlCreator);
    }
    public function build(PDO $pdo, array $inputParameters){
        parent::checkProcedureParameter($this->method);

        $procedureName = $this->annote->getProcedure($this->method);
        $meta = HermitDatabaseMetaFactory::get($pdo);
        $info = $meta->getProcedureInfo($procedureName);

        $dbms = HermitDatabaseMetaFactory::getDbms($pdo);
        $parameter = new HermitProcedureParameter($info, $dbms);
        $parameter->setInputParameters($inputParameters);
        
        $setupSql = self::preparedSql($parameter, $this->sqlCreator->createSetupSql());
        
        $logger = HermitLoggerManager::getLogger();
        if($logger->isDebugEnabled()){
            $logger->debug('{%s} preparedSql: "%s"', __CLASS__, $sql);
        }
        
        $statement = $pdo->prepare($setupSql);
        return new HermitDefaultStatement($parameter, $statement);
    }
}
