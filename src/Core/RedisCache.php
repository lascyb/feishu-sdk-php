<?php

namespace feishu\Core;

use Psr\SimpleCache\CacheInterface;
use Predis\Client as PredisClient;

/**
 * RedisCache: PSR-16 compatible adapter using predis/predis
 */
class RedisCache implements CacheInterface
{
    private PredisClient $client;
    private string $prefix;

    public function __construct(PredisClient $client, string $prefix = 'feishu:')
    {
        $this->client = $client;
        $this->prefix = $prefix;
    }

    public function get($key, $default = null)
    {
        $value = $this->client->get($this->prefix . $key);
        if ($value === null) {
            return $default;
        }
        return unserialize($value);
    }

    public function set($key, $value, $ttl = null): bool
    {
        $fullKey = $this->prefix . $key;
        $value = serialize($value);
        if ($ttl === null) {
            $this->client->set($fullKey, $value);
            return true;
        }
        $this->client->setex($fullKey, (int)$ttl, $value);
        return true;
    }

    public function delete($key): bool
    {
        $this->client->del([$this->prefix . $key]);
        return true;
    }

    public function clear(): bool
    {
        // careful: only delete keys with our prefix
        $keys = $this->client->keys($this->prefix . '*');
        if (!empty($keys)) {
            $this->client->del($keys);
        }
        return true;
    }

    public function getMultiple($keys, $default = null): iterable
    {
        $result = [];
        $fullKeys = array_map(function ($k) { return $this->prefix . $k; }, $keys);
        $values = $this->client->mget($fullKeys);
        foreach ($keys as $i => $k) {
            $v = $values[$i] ?? null;
            $result[$k] = $v === null ? $default : unserialize($v);
        }
        return $result;
    }

    public function setMultiple($values, $ttl = null): bool
    {
        foreach ($values as $k => $v) {
            $this->set($k, $v, $ttl);
        }
        return true;
    }

    public function deleteMultiple($keys): bool
    {
        $fullKeys = array_map(function ($k) { return $this->prefix . $k; }, $keys);
        $this->client->del($fullKeys);
        return true;
    }

    public function has($key): bool
    {
        return $this->client->exists($this->prefix . $key) === 1;
    }
}
