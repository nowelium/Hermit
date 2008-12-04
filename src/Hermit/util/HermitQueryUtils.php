<?php

/**
 * @author yusuke.hata
 */
abstract class HermitQueryUtils {
    public static function addQuery($sql, $query){
        $buf = '';
        if(false === stripos($sql, 'WHERE')){
            $buf .= ' ';
            $buf .= 'WHERE';
            $buf .= ' ';
        } else if(preg_match('/WHERE(\s*)$/i', $sql)){
            //
            // query has (AND | OR)
            //
            if(preg_match('/^(\s)*(AND|OR)/i', $query)){
                $buf .= ' ';
                $buf .= '1 = 1';
                $buf .= ' ';
            }
        }
        //
        // query has not (AND | OR)
        //
        if(!preg_match('/^(\s)*(AND|OR)/i', $query)){
            $buf .= ' ';
            $buf .= 'AND';
            $buf .= ' ';
        }
        return $buf;
    }
}