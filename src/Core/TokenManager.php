<?php

namespace feishu\Core;

use Psr\SimpleCache\CacheInterface;
use feishu\HttpTransport;
use feishu\FeishuException;

/**
 * TokenManager: manage tenant/app access tokens with PSR-16 cache support.
 */
class TokenManager
{
    private Config $config;
    private HttpTransport $transport;
    private ?CacheInterface $cache;

    public function __construct(Config $config, ?HttpTransport $transport = null, ?CacheInterface $cache = null)
    {
        $this->config = $config;
        $this->transport = $transport ?: new HttpTransport();
        $this->cache = $cache;
    }

    /**
     * Get tenant_access_token. Cached by PSR-16 if available.
     * Cache key: feishu:tenant_access_token:{appId}
     */
    public function getTenantAccessToken(): string
    {
        $cacheKey = $this->tenantAccessTokenCacheKey();
        if ($this->cache) {
            $cached = $this->cache->get($cacheKey);
            if (is_string($cached) && $cached !== '') {
                return $cached;
            }
        }

        $url = $this->config->baseUrl . '/auth/v3/tenant_access_token/internal';
        $body = json_encode([
            'app_id' => $this->config->appId,
            'app_secret' => $this->config->appSecret,
        ], JSON_UNESCAPED_UNICODE);

        $raw = $this->transport->request('POST', $url, [
            'Content-Type: application/json; charset=utf-8',
        ], $body);

        $result = json_decode($raw, true);
        if (!is_array($result) || (int)($result['code'] ?? -1) !== 0) {
            throw new FeishuException('获取 tenant_access_token 失败：' . ($result['msg'] ?? $raw), (int)($result['code'] ?? 0), $raw);
        }

        $token = (string)($result['tenant_access_token'] ?? '');
        $expire = (int)($result['expire'] ?? 7200);
        $ttl = max($expire - 120, 60);

        if ($token === '') {
            throw new FeishuException('获取 tenant_access_token 失败：token 为空', 0, $raw);
        }

        if ($this->cache) {
            // set with TTL seconds
            $this->cache->set($cacheKey, $token, $ttl);
        }

        return $token;
    }

    /**
     * Cache key helper
     */
    private function tenantAccessTokenCacheKey(): string
    {
        return 'feishu:tenant_access_token:' . $this->config->appId;
    }

    /**
     * Get app_access_token (not tenant). Keep for completeness.
     * Cache key: feishu:app_access_token:{appId}
     */
    public function getAppAccessToken(): string
    {
        $cacheKey = 'feishu:app_access_token:' . $this->config->appId;
        if ($this->cache) {
            $cached = $this->cache->get($cacheKey);
            if (is_string($cached) && $cached !== '') {
                return $cached;
            }
        }

        $url = $this->config->baseUrl . '/auth/v3/app_access_token/internal';
        $body = json_encode([
            'app_id' => $this->config->appId,
            'app_secret' => $this->config->appSecret,
        ], JSON_UNESCAPED_UNICODE);

        $raw = $this->transport->request('POST', $url, [
            'Content-Type: application/json; charset=utf-8',
        ], $body);

        $result = json_decode($raw, true);
        if (!is_array($result) || (int)($result['code'] ?? -1) !== 0) {
            throw new FeishuException('获取 app_access_token 失败：' . ($result['msg'] ?? $raw), (int)($result['code'] ?? 0), $raw);
        }

        $token = (string)($result['app_access_token'] ?? '');
        $expire = (int)($result['expire'] ?? 7200);
        $ttl = max($expire - 120, 60);

        if ($token === '') {
            throw new FeishuException('获取 app_access_token 失败：token 为空', 0, $raw);
        }

        if ($this->cache) {
            $this->cache->set($cacheKey, $token, $ttl);
        }

        return $token;
    }
}
