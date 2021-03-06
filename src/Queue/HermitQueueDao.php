<?php

/**
 * @author nowelium
 */
interface HermitQueueDao {
    const wait_SQL = 'SELECT queue_wait(/*table*/"table", /*timeout*/5)';
    const wait_VALUE_TYPE = 'NUM';
    public function wait($table, $timeout);
    
    const end_SQL = 'SELECT queue_end()';
    public function end();
    
    const abort_SQL = 'SELECT queue_abort()';
    public function abort();
    
    const get_VALUE_TYPE = 'OBJ';
    public function get();
}