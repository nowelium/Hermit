<?php

/**
 * @author nowelium
 */
class HermitMandatoryTx extends AbstractHermitTx {
    public function proceed(HermitProxy $proxy, $name, array $parameters){
        if(!$this->hasTransaction()){
            throw new LogicException('No transaction');
        }
        return $proxy->request($name, $parameters);
    }
}
