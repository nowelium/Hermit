<?php

/**
 * @author nowelium
 */
class HermitStatementBuilder {
    const IF_COMMEND_REGEXP = '/\/\*IF\s(.*?)\*\/(.*?)\/\*END\*\//ms';
    const ELSE_COMMEND_REGEXP = '/(.*)--ELSE\s(.*)/ms';
    const SQL_COMMENT_REGEXP = '/(\/\*([^\*\/]*)\*\/)(\w+|((\'|")([^(\'|")]*)(\'|")))?/m';
    protected $targetClass;
    protected $method;
    protected $sqlCreator;
    
    public function __construct(ReflectionClass $targetClass, ReflectionMethod $method, HermitSqlCreator $sqlCreator){
        //
        // TODO: ここのコンストラクタパラメータの引き継ぎがめんどいので、setterにすること
        //
        $this->targetClass = $targetClass;
        $this->method = $method;
        $this->sqlCreator = $sqlCreator;
    }
    public function build(PDO $pdo, array $inputParameters){
        $parameter = HermitSqlParameterFactory::createParameterType($this->method);
        $parameter->setInputParameters($inputParameters);
        $parameter->setTargetClass($this->targetClass);
        $parameter->setTargetMethod($this->method);
        
        $sql = $this->sqlCreator->createSql($pdo);
        if(preg_match(self::IF_COMMEND_REGEXP, $sql)){
            return new HermitLazyStatement($this, $parameter, $pdo, $sql);
        }
        $sql = self::preparedSql($parameter, $sql);
        
        $logger = HermitLoggerManager::getLogger();
        if($logger->isDebugEnabled()){
            $logger->debug('{%s} preparedSql: "%s"', __CLASS__, $sql);
        }
        
        $statement = $pdo->prepare($sql);
        return new HermitDefaultStatement($parameter, $statement);
    }
    
    public function rebuild(HermitSqlParameter $parameter, PDO $pdo, $sql){
        $sql = self::preparedSql($parameter, $sql);
        
        $logger = HermitLoggerManager::getLogger();
        if($logger->isDebugEnabled()){
            $logger->debug('{%s} preparedSql: "%s"', __CLASS__, $sql);
        }
        
        $statement = $pdo->prepare($sql);
        return new HermitDefaultStatement($parameter, $statement);
    }
    
    protected static function preparedSql(HermitSqlParameter $parameter, $sql){
        return preg_replace_callback(self::SQL_COMMENT_REGEXP, array($parameter, 'match'), $sql);
    }
}
