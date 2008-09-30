<?php

/**
 * @author nowelium
 */
class HermitDelegater implements HermtCallWrapper {
    private $delegaters = array();
    private function __construct(){
        // nop
    }
    public static function delegate(Hermit $hermit, $methodName, HermitDelegateProxy $delgator){
        $instance = null;
        if(!HermitRegister::hasCall(__CLASS__, $hermit)){
            $instance = new self;
            HermitRegister::putCall(__CLASS__, $hermit, $instance);
        }
        $instance->delegaters[$methodName] = $responder;
    }
    
    public function has($methodName){
        return isset($this->delegaters[$methodName]);
    }
    
    public function execute(HermitProxy $proxy, $name, array $parameters){
        $delegater = $this->delegaters[$methodName];
        return $delegater->request($name, $parameters);
    }
}
