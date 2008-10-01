<?php

/**
 * @author nowelium
 */
class HermitRequiredTx extends AbstractHermitTx {
    public function proceed(HermitProxy $proxy, $name, array $parameters){
        $began = false;

        if(!$this->hasTransaction()){
            $this->begin();
            $began = true;
        }
        $ret = null;
        try {
            $ret = $proxy->request($name, $parameters);
            if($began){
                $this->commit();
            }
            return $ret;
        } catch(Exception $e){
            if($began){
                $this->complete($e);
            }
            throw $e;
        }
    }
}
