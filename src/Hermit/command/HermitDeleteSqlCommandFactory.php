<?php

/**
 * @author nowelium
 */
class HermitDeleteSqlCommandFactory extends AbstractHermitUpdateSqlCommandFactory {
    public function create(PDO $pdo, HermitContext $context){
        $sqlCreator = $this->getStaticSqlCreator($pdo);
        if(null === $sqlCreator){
            $sqlCreator = new HermitAutoDeleteSqlCreator;
        }
        $sqlCreator->initialize($pdo, $this->method, $this->annote);
        $valueType = HermitValueTypeFactory::create($this->annote, $this->method);
        
        $command = new HermitDeleteCommand;
        $command->setContext($context);
        $command->setMethod($this->method);
        $command->setSqlCreator($sqlCreator);
        $command->setValueType($valueType);
        return $command;
    }
}