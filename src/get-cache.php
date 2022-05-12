<?php

require_once __DIR__ . '/vendor/autoload.php';

$redis = new Arch6\CacheWrapper();
$value = $redis->xfetch();

var_dump($value);
