<?php

/**
 * @author nowelium
 */
class HermitNeverTx extends AbstractHermitTx {
    public function proceed(HermitProxy $proxy, $name, array $parameters){
        if($this->hasTransaction()){
            throw new LogicException('Already associated with another transaction');
        }
        return $proxy->request($name, $parameters);
    }
}
