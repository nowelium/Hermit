<?php

HermitDaoManager::set(__CLASS__, 'HogeDao');
HermitDataSourceManager::set(__CLASS__, $pdo);
HermitTransactionManager::set(__CLASS__, HermitMadatoryTx::create());
HermitTransactionManager::set('Foo', HermitRequiredTx::create());

$hermit = new Hermit;
$hoge = $hermit->selectHoge();

$fooHermit = new Hermit('Foo');
$hermit->delegate('selectFoo', $fooHermit);
$foo = $hermit->selectFoo();

HermitListener::add($hermit, 'selectBar', new HermitResponder($this, 'onResult'));

$hermit->addListener('selectBar', $hogeHermit, new HermitResponder($this, 'onResult'));
$hermit->addListener('selectBar', $fooHermit, new HermitResponder($this, 'onResult'));
$hermit->addListener('selectBar', $barHermit, new HermitResponder($this, 'onResult'));
$hermit->selectBar();

class Hermit {
    public function __call($name, $params = array()){
        if(isset($this->listeners[$name)){
            $listener = (array) $this->listeners[$name];
            $proxies = $listener->getProxies();
            foreach($proxies as $proxy){
                $result = self::__request($name, $params);
                call_user_func_array($listener->binder(), array($result));
            }
            return null;
        }
        if(isset($this->delegator[$name])){
            return self::__request($this->delegator[$name], $name, $params);
        }
        return self::__request($this->proxy, $name, $params);
    }
    protected static function __request(self $self, $name, array $params){
        return $self->request($name, $params);
    }
}
