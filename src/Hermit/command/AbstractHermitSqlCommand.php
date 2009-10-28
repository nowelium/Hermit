<?php

/**
 * @author nowelium
 */
abstract class AbstractHermitSqlCommand implements HermitSqlCommand {
    protected $context;
    protected $method;
    protected $sqlCreator;
    protected $type;
    protected $statement;
    public function setContext(HermitContext $ctx){
        $this->context = $ctx;
    }
    public function setMethod(ReflectionMethod $method){
        $this->method = $method;
    }
    public function setSqlCreator(HermitSqlCreator $sqlCreator){
        $this->sqlCreator = $sqlCreator;
    }
    public function setValueType(HermitValueType $type){
        $this->type = $type;
    }
    protected function getConnection($event = HermitEvent::UNKNOWN){
        return HermitDataSourceManager::get(
            $this->context->getName(),
            $this->method->getName(),
            $event
        );
    }
    protected function buildStatement($event = HermitEvent::UNKNOWN, array $parameters){
        if($this->context->isBatchMode()){
            //
            // statement を使いまわしてしまうと
            // SQLSTATE[HY000]: General error: 2006 MySQL server has gone away{123}
            // となってしまうため、batchMode を利用する際は
            // PDO::MYSQL_ATTR_USE_BUFFERED_QUERY を true にして利用すること
            //
            if(null === $this->statement){
                $this->statement = self::createStatement($event, $parameters);
            }
            return $this->statement;
        }
        return $this->createStatement($event, $parameters);
    }
    
    private function createStatement($event = HermitEvent::UNKNOWN, array $parameters){
        $pdo = $this->getConnection($event);
        $builder = new HermitStatementBuilder($this->context->getTargetClass(), $this->method, $this->sqlCreator);
        return $builder->build($pdo, $parameters);
    }

    protected static function setupProcedureConnection(PDO $pdo){
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        if(0 === strcasecmp('mysql', $driver)){
            //
            // procedure の out parameter などを取得しようとする際などにおいて
            // 既に他のステートメントが発行されてしまっていると
            // ステートメントを閉じても下記エラーが発生してしまう
            // そのため、procedure を利用する時は direct query を使用する
            //
            // PDOException: SQLSTATE[HY000]: General error: 2014 Cannot execute queries while other unbuffered queries are active.
            // Consider using PDOStatement::fetchAll().
            // Alternatively, if your code is only ever going to run against mysql, you may enable query buffering by setting the PDO::MYSQL_ATTR_USE_BUFFERED_QUERY attribute.
            //
            $pdo->setAttribute(PDO::MYSQL_ATTR_DIRECT_QUERY, true);
        }
    }
}
