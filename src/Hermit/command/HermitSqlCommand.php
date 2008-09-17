<?php

interface HermitSqlCommand {
    public function execute(PDO $pdo, array $params);
}
