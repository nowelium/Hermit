<?php

/**
 * @author nowelium
 */
class HermitNotSupportedTx extends AbstractHermitTx {
    public function proceed(HermitProxy $proxy, $name, array $parameters){
        try {
            return $proxy->request($name, $parameters);
        } catch(Exception $e){
            throw $e;
        }
    }
}
