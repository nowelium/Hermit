<?php

/**
 * @author nowelium
 */
class HermitStatementBuilder {
    const IF_COMMEND_REGEXP = '/\/\*IF\s([^(\*\/)]+|.+)\*\//ms';
    const SQL_COMMENT_REGEXP = '/(\/\*([^\*\/]*)\*\/)(\w+|((\'|")([^(\'|")]*)(\'|")))?/m';
    private $method;
    private $sqlCreator;
    public function __construct(ReflectionMethod $method, HermitSqlCreator $sqlCreator){
        $this->method = $method;
        $this->sqlCreator = $sqlCreator;
    }
    public function build(PDO $pdo){
        $parameter = $this->createParameterType();
        $sql = $this->sqlCreator->createSql($pdo);
        if(preg_match(self::IF_COMMEND_REGEXP, $sql)){
            return new HermitLazyStatement($parameter, $pdo, $sql);
        }
        $sql = self::preparedSql($parameter, $sql);
        return new HermitDefaultStatement($parameter, $pdo->prepare($sql));
    }

    protected static function preparedSql(HermitSqlParameter $parameter, $sql){
        return preg_replace_callback(self::SQL_COMMENT_REGEXP, array($parameter, 'match'), $sql);
    }

    protected function createParameterType(){
        $numOfParams = $this->method->getNumberOfParameters();
        if(0 === $numOfParams){
            return new HermitSqlParameterNull;
        }
        $params = $this->method->getParameters();
        if(1 === $numOfParams){
            return self::createParameterTypeWithIndex($params[0], 0);
        }
        $parameter = new HermitSqlParameterMixed;
        foreach($params as $index => $param){
            $parameter->add(self::createParameterTypeWithIndex($param, $param->getPosition()));
        }
        return $parameter;
    }
    protected static function createParameterTypeWithIndex(ReflectionParameter $ref, $index){
        if($ref->isArray()){
            $parameter = new HermitSqlParameterSequence;
            $parameter->add($ref->getName(), $ref->getPosition());
            return $parameter;
        }
        $class = $ref->getClass();
        if(is_null($class)){
            $parameter = new HermitSqlParameterHash;
            $parameter->add($ref->getName(), $ref->getPosition());
            return $parameter;
        }
        $parameter = new HermitSqlParameterClassHash($class);
        $parameter->add($ref->getName(), $ref->getPosition());
        return $parameter;
    }
}
