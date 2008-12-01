<?php

/**
 * @author nowelium
 */
abstract class HermitNamingUtils {
    
    const PROCEDURE_NAMES = '/^(proc|call)/i';
    const INSERT_NAMES = '/^(insert|create|add)/i';
    const UPDATE_NAMES = '/^(update|modify|store)/i';
    const DELETE_NAMES = '/^(delete|remove)/i';
    
    public static function isProcedure($methodName){
        return 1 === preg_match(self::PROCEDURE_NAMES, $methodName);
    }
    public static function isInsert($methodName){
        return 1 === preg_match(self::INSERT_NAMES, $methodName);
    }
    public static function isUpdate($methodName){
        return 1 === preg_match(self::UPDATE_NAMES, $methodName);
    }
    public static function isDelete($methodName){
        return 1 === preg_match(self::DELETE_NAMES, $methodName);
    }
    public static function isSelect($methodName){
        if(self::isProcedureMethod($methodName)){
            return false;
        }
        if(self::isInsertMethod($methodName)){
            return false;
        }
        if(self::isUpdateMethod($methodName)){
            return false;
        }
        if(self::isDeleteMethod($methodName)){
            return false;
        }
        return true;
    }
}