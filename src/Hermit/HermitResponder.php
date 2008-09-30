<?php

/**
 * @author nowelium
 */
class HermitResponder implements HermtCallWrapper {
    private $responders = array();
    private function __construct(){
        // nop
    }
    public static function listen(Hermit $hermit, $methodName, HermitRespondProxy $responder){
        $instance = null;
        if(!HermitRegister::hasCall(__CLASS__, $hermit)){
            $instance = new self;
            HermitRegister::putCall(__CLASS__, $hermit, $instance);
        }
        if(!isset($instance->responders[$methodName])){
            $instance->responders[$methodName] = array();
        }
        $instance->responders[$methodName][] = $responder;
    }
    
    public function has($methodName){
        return isset($this->responders[$methodName]);
    }
    
    public function execute(HermitProxy $proxy, $name, array $parameters){
        $response = $proxy->request($name, $parameters);
        $responders = $this->responders[$name];
        foreach($responders as $responder){
            $responder->request($name, array($response));
        }
        return $response;
    }
}