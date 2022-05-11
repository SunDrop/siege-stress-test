<?php

use Arch6\DbWrapper;

require_once __DIR__ . '/vendor/autoload.php';

$redis = new Arch6\Redis();
$value = $redis->xfetch();
if (false === $value) {
    $db = new DbWrapper($_ENV['DATABASE_URL'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    $value = $db->getAll();
    $redis->set($value);
}

var_dump($value);
