<?php
require dirname(__FILE__) . '/setup.php';
$test = new lime_test;
$test->diag(basename(__FILE__));

HermitDaoManager::set('Hoge', 'A01Dao');
HermitDaoManager::set('Foo', 'A02Dao');

$test->is(HermitDaoManager::get('Hoge'), 'A01Dao');
$test->is(HermitDaoManager::get('Foo'), 'A02Dao');

class Hoge {
    public function get(){
        return HermitDaoManager::get(__CLASS__);
    }
    public function of($target){
        return HermitDaoManager::get($target);
    }
}
class Foo {
    public function get(){
        return HermitDaoManager::get(__CLASS__);
    }
}
class Bar {
    public function get(){
        return HermitDaoManager::get(__CLASS__);
    }
}

$hoge = new Hoge;
$foo = new Foo;
$bar = new Bar;
$test->is($hoge->get(), 'A01Dao');
$test->is($hoge->of('Foo'), 'A02Dao');
$test->is($foo->get(), 'A02Dao');
$test->is($bar->get(), null);

$test->is(HermitDaoManager::has('Hoge'), true);
$test->is(HermitDaoManager::has('Foo'), true);
$test->is(HermitDaoManager::has('A01Dao'), false);
$test->is(HermitDaoManager::has('A02Dao'), false);

