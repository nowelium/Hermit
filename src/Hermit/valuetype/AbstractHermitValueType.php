<?php

/**
 * @author nowelium
 */
abstract class AbstractHermitValueType implements HermitValueType {
    protected $annote;
    protected $method;
    protected $value;
    public function __construct(HermitAnnote $annote, ReflectionMethod $method, $value){
        $this->annote = $annote;
        $this->method = $method;
        $this->value = $value;
    }
}
