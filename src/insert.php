<?php

use Arch6\DbWrapper;

require_once __DIR__ . '/vendor/autoload.php';

$db = new DbWrapper($_ENV['DATABASE_URL'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
$db->insert();
