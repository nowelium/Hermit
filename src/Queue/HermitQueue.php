<?php

/**
 * @author nowelium
 */
interface HermitQueue {
    public function fetch();
    public function wait();
    public function end();
    public function abort();
}