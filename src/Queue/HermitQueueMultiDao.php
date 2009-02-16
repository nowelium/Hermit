<?php

/**
 * @author nowelium
 */
interface HermitQueueMultiDao {
    const wait_SQL = 'SELECT queue_wait(/*multival*/"multival")';
    const wait_VALUE_TYPE = HermitQueueDao::wait_VALUE_TYPE;
    public function wait(array $multival);
    
    const end_SQL = HermitQueueDao::end_SQL;
    public function end($table);
    
    const abort_SQL = HermitQueueDao::abort_SQL;
    public function abort();
    
    const get_VALUE_TYPE = HermitQueueDao::get_VALUE_TYPE;
    public function get();
}