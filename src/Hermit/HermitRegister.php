<?php

/**
 * @author nowelium
 */
abstract class HermitRegister extends Hermit {
    public static function putBehavior($key, HermitBehaviorWrapper $wrapper){
        parent::$behaviors[$key] = $wrapper;
    }
    public static function hasBehavior($key){
        return isset(parent::$behaviors[$key]);
    }
    public static function getBehavior($key){
        return parent::$behaviors[$key];
    }
    
    public static function putCall($key, Hermit $hermit, HermitCallWrapper $wrapper){
        $hermit->calls[$key] = $wrapper;
    }
    public static function hasCall($key, Hermit $hermit){
        return isset($hermit->calls[$key]);
    }
    public static function getCall($key, Hermit $hermit){
        return $hermit->calls[$key];
    }
}