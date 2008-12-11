<?php

$hermit = new Hermit('HogeQueue');
echo 'please, Program stop = Ctrl-C', PHP_EOL;
$id = 0;
while(true){
    $param = new HermitParam;
    $param->id = $id++;
    $param->name = crc32((string) $id);
    $hermit->add($param);
}