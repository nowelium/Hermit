<?php

/**
 * @author nowelium
 */
class HermitRequiresNewTx extends AbstractHermitTx {
    public function proceed(HermitProxy $proxy, $name, array $parameters){
        $this->begin();
        try {
            $ret = $proxy->request($name, $parameters);
            $this->commit();
            return $ret;
        } catch(Exception $e){
            $this->complete($e);
            throw $e;
        }
    }
}
