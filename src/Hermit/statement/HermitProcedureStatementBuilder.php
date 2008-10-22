<?php

/**
 * @author nowelium
 */
class HermitProcedureStatementBuilder extends HermitStatementBuilder {
    protected $method;
    protected $annote;
    protected $sqlCreator;

    protected static $procedureParameters = array(
        'mysql' => 'HermitMySqlProcedureParameter'
    );

    public function __construct(ReflectionMethod $method, HermitAnnote $annote, HermitSqlCreator $sqlCreator){
        parent::__construct($method, $sqlCreator);
        $this->method = $method;
        $this->annote = $annote;
        $this->sqlCreator = $sqlCreator;
    }

    public function build(PDO $pdo){
        self::checkProcedureParameter($this->method);

        $procedureName = $this->annote->getProcedure($this->method);
        $meta = HermitDatabaseMetaFactory::get($pdo);
        $info = $meta->getProcedureInfo($procedureName);

        $dbms = HermitDatabaseMetaFactory::getDbms($pdo);
        $parameter = null;
        if(isset(self::$procedureParameters[$dbms])){
            $className = self::$procedureParameters[$dbms];
            $parameter = new $className($info, $dbms);
        } else {
            $parameter = new HermitProcedureParameter($info, $dbms);
        }
        
        $sql = self::preparedSql($parameter, $this->sqlCreator->createSql());
        
        $logger = HermitLoggerManager::getLogger();
        if($logger->isDebugEnabled()){
            $logger->debug('{%s} preparedSql: "%s"', __CLASS__, $sql);
        }
        $statement = $pdo->prepare($sql);
        return new HermitDefaultStatement($parameter, $statement);
    }
    
    protected function checkProcedureParameter(ReflectionMethod $method){
        $parameters = $method->getParameters();
        $count = count($parameters);
        if(1 < $count){
            throw new InvalidArgumentException('invalid parameters: method parameter must be empty or once HermitParam type');
        }
        if(1 === $count){
            $param = $parameters[0];
            $paramClass = $param->getClass();
            if(null === $paramClass){
                throw new InvalidArgumentException('parameter must be empty or once HermitParam type. cause "' . $param->getName() . '"');
            }

            if('HermitParam' === $paramClass->getName()){
                return;
            }
            if($paramClass->isSubclassOf(new ReflectionClass('HermitParam'))){
                return;
            }
            while($parent = $paramClass->getParentClass()){
                if('HermitParam' === $parent->getName()){
                    return;
                }
            }
            throw new InvalidArgumentException('invalid parameter type: ' . $param->getName() . ' type was not instanceof HermitParam');
        }
    }
}
