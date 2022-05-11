<?php

namespace Arch6;

class Redis
{
    public const CACHE_KEY = 'arch6';

    public const TTL_SEC = 30;

    public const DELTA_MS = 250;

    private $redis;

    public function __construct()
    {
        $this->redis = new \Predis\Client(['host' => $_ENV['REDIS_HOST']]);
    }

    public function set($value)
    {
        $this->redis->set(self::CACHE_KEY, json_encode($value), 'EX', self::TTL_SEC);
    }

    public function get()
    {
        return $this->redis->get(self::CACHE_KEY);
    }

    public function ttl()
    {
        return $this->redis->ttl(self::CACHE_KEY);
    }

    public function xfetch()
    {
        $value = $this->get();
        $ttl = $this->ttl();

        if (!$value || ((time() + self::DELTA_MS) >= time() + ($ttl * 1000))) {
            return false;
        }

        return $value;
    }
}
