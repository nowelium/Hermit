<?php

/**
 * @author nowelium
 */
interface HermitProxy extends HermitDao {
    public function request($name, array $parameters);
}
