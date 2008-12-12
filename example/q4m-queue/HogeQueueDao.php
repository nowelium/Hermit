<?php

interface HogeQueueDao extends HermitQueueDao {
    const TABLE = 'hoge_queue';
    const COLUMNS = 'id, name';
    public function add(HermitParam $param);
}