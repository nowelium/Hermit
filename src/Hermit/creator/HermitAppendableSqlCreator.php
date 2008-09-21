<?php

interface HermitAppendableSqlCreator {
    public function addQuery($queryString);
    public function addLimit($limit);
}
