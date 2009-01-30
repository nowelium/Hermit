<?php

/**
 * @author nowelium
 */
class HermitExpression {
    protected $parameter;
    protected $parameterValue;
    public function __construct(HermitSqlParameter $parameter, $parameterValue){
        $this->parameter = $parameter;
        $this->parameterValue = $parameterValue;
    }
    
    public function build($sql){
        $callback = array($this, 'callback');
        return preg_replace_callback(HermitStatementBuilder::IF_COMMEND_REGEXP, $callback, $sql);
    }
    
    public function callback($matches){
        $expression = $matches[1];
        $statement = $matches[2];
        
        if(preg_match(HermitStatementBuilder::ELSE_COMMEND_REGEXP, $statement, $elseMatch)){
            $trueStatement = $elseMatch[1];
            $falseStatement = $elseMatch[2];
            $result = $this->parameter->binoCreate($expression, $trueStatement, $falseStatement, $this->parameterValue);
            if(HermitSqlParameter::BINO_TRUE_MATCHED === $result){
                return $trueStatement;
            }
            if(HermitSqlParameter::BINO_FALSE_MATCHED === $result){
                return $falseStatement;
            }
            return '';
        }
        $result = $this->parameter->monoCreate($expression, $statement, $this->parameterValue);
        if(HermitSqlParameter::MONO_MATCHED === $result){
            return $statement;
        }
        return '';
    }
}