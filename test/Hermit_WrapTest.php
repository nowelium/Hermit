<?php
require dirname(__FILE__) . '/setup.php';

class HogeWrapper implements HermitBehaviorWrapper {
    public function has($tarrgetClass){
    }
    public function createProxy(HermitProxy $proxy, $targetClass){
    }
}
class FooWrapper extends HogeWrapper {
}

class A extends Hermit {
    private static $instance = null;
    private static function getInstance(){
        if(null === self::$instance){
            self::$instance = new self;
        }
        return self::$instance;
    }
    public static function add(HermitBehaviorWrapper $w){
        Hermit::$wrappers[] = $w;
    }

    public static function getWrappers(){
        return Hermit::$wrappers;
    }
}

$test = new lime_test;

$hoge = new HogeWrapper;
A::add($hoge);
$wrappers = A::getWrappers();
$test->is(count($wrappers), 1);
$test->ok($hoge === $wrappers[0]);

$foo = new FooWrapper;
A::add($foo);
$wrappers = A::getWrappers();
$test->is(count($wrappers), 2);
$test->ok($foo === $wrappers[1]);
$test->ok($hoge === $wrappers[0]);

