<?php

/**
 * @author nowelium
 */
abstract class HermitListener extends Hermit {
    public static function listen(Hermit $hermit, $methodName, HermitRespondProxy $responder){
        if(!isset($hermit->listeners[$methodName])){
            $hermit->listeners[$methodName] = array();
        }
        $hermit->listeners[$methodName][] = $responder;
    }
    public static function delegate(Hermit $hermit, $methodName, HermitDelegateProxy $delgator){
        $hermit->delegators[$methodName] = $delegator;
    }
}
