<?php

namespace feishu\Core;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException as PsrInvalidArgumentException;

/**
 * MemoryCache: a simple in-memory PSR-16 compatible cache implementation for testing and default.
 */
class MemoryCache implements CacheInterface
{
    private array $data = [];

    /**
     * @param string $key
     * @return mixed|null
     * @throws PsrInvalidArgumentException
     */
    public function get($key, $default = null)
    {
        $this->assertKey($key);
        if (!isset($this->data[$key])) {
            return $default;
        }
        $item = $this->data[$key];
        if ($item['expires_at'] !== null && time() >= $item['expires_at']) {
            unset($this->data[$key]);
            return $default;
        }
        return $item['value'];
    }

    public function set($key, $value, $ttl = null): bool
    {
        $this->assertKey($key);
        $expiresAt = null;
        if ($ttl !== null) {
            $expiresAt = time() + (int)$ttl;
        }
        $this->data[$key] = ['value' => $value, 'expires_at' => $expiresAt];
        return true;
    }

    public function delete($key): bool
    {
        $this->assertKey($key);
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
        return true;
    }

    public function clear(): bool
    {
        $this->data = [];
        return true;
    }

    public function getMultiple($keys, $default = null): iterable
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }
        return $result;
    }

    public function setMultiple($values, $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }
        return true;
    }

    public function deleteMultiple($keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }
        return true;
    }

    public function has($key): bool
    {
        $this->assertKey($key);
        if (!isset($this->data[$key])) {
            return false;
        }
        $item = $this->data[$key];
        if ($item['expires_at'] !== null && time() >= $item['expires_at']) {
            unset($this->data[$key]);
            return false;
        }
        return true;
    }

    private function assertKey($key)
    {
        if (!is_string($key) || $key === '') {
            throw new class("Invalid cache key") extends \Exception implements PsrInvalidArgumentException {};
        }
    }
}
