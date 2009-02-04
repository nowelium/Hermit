<?php

/**
 * @author nowelium
 */
class HermitInsertSqlCommandFactory extends AbstractHermitUpdateSqlCommandFactory {
    public function create(PDO $pdo, HermitContext $context){
        $sqlCreator = $this->getStaticSqlCreator($pdo);
        if(null === $sqlCreator){
            $sqlCreator = new HermitAutoInsertSqlCreator;
        }
        $sqlCreator->initialize($pdo, $this->method, $this->annote);
        $valueType = HermitValueTypeFactory::create($this->annote, $this->method);
        
        $batchMode = $this->annote->getBatchMode($this->method);
        $context->setBatchMode($batchMode);
        
        $command = new HermitInsertCommand;
        $command->setContext($context);
        $command->setMethod($this->method);
        $command->setSqlCreator($sqlCreator);
        $command->setValueType($valueType);
        return $command;
    }
}