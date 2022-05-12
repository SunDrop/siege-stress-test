<?php

require_once __DIR__ . '/vendor/autoload.php';

$redis = new Arch6\Redis();
$value = $redis->xfetch();

var_dump($value);
