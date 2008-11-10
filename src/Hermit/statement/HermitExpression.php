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
            return $this->parameter->binoCreate($expression, $trueStatement, $falseStatement, $this->parameterValue);
        }
        return $this->parameter->monoCreate($expression, $statement, $this->parameterValue);
    }
}