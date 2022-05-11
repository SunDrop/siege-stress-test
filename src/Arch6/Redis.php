<?php

namespace Arch6;

class Redis
{
    public const CACHE_KEY = 'arch6';

    public const TTL_SEC = 30;

    public const BETA = 1;

    private $redis;

    public function __construct()
    {
        $this->redis = new \Predis\Client(['host' => $_ENV['REDIS_HOST']]);
    }

    public function set($value)
    {
        $this->redis->set(self::CACHE_KEY, $value, 'EX', self::TTL_SEC);
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
        $redisValue = $this->get();
        $info = @unpack('Pdelta/Pexpire/Z*value', $redisValue);
        $delta = @$info['delta'];
        $expire = @$info['expire'];
        $value = @unserialize($info['value']);

        if (!$redisValue || time() - $delta * self::BETA * log(rand(0,1)) >= $expire) {
            $start = time();
            $db = new DbWrapper($_ENV['DATABASE_URL'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
            $value = $db->getAll();
            $delta = time() - $start;
            $expire = time() + self::TTL_SEC;
            $redisValue = pack('PPZ*', $delta, $expire, serialize($value));

            $this->set($redisValue);
        }

        return $value;
    }
}
