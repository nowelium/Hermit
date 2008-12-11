<?php

$hermit = new Hermit('HogeQueue');
$it = new HermitQueueIterator($hermit);
$it->setTable('hoge_queue');
$it->setTimeout(2);

echo 'start', PHP_EOL;
$filter = new HermitQueueFilter($it);
foreach($filter as $key => $queue){
    var_dump($key, $queue->get());
    $queue->complete();
    
    echo 'next', PHP_EOL;
    sleep(2);
}