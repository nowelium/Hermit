<?php

/**
 * @author nowelium
 */
class HermitProcedureStatementBuilder extends HermitStatementBuilder {
    private $method;
    private $annote;
    private $sqlCreator;
    public function __construct(ReflectionMethod $method, HermitAnnote $annote, HermitSqlCreator $sqlCreator){
        $this->method = $method;
        $this->annote = $annote;
        $this->sqlCreator = $sqlCreator;
    }

    public function build(PDO $pdo){
        $this->checkProcedureParameter();

        $procedureName = $this->annote->getProcedure($this->method);
        $meta = HermitDatabaseMetaFactory::get($pdo);
        $info = $meta->getProcedureInfo($procedureName);

        $dbms = HermitDatabaseMetaFactory::getDbms($pdo);
        $parameter = new HermitProcedureParameter($info, $dbms);
        $sql = $this->sqlCreator->createSql($pdo);
        $sql = self::preparedSql($parameter, $sql);
        return new HermitStatement($parameter, $pdo->prepare($sql));
    }
    
    protected function checkProcedureParameter(){
        $parameters = $this->method->getParameters();
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
