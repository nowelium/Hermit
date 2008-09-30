<?php
require dirname(__FILE__) . '/setup.php';

class Hoge implements HermitBehaviorWrapper {
    public function has($targetClass){
    }
    public function createProxy(HermitProxy $proxy, $targetClass){
    }
}
