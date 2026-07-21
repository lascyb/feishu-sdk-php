<?php

// Update Auth to optionally use TokenManager (PSR-16 aware)

namespace feishu;

use feishu\Core\TokenManager;

/**
 * Auth 飞书 tenant_access_token 管理
 */
class Auth
{
    /** @var string */
    private $appId;

    /** @var string */
    private $appSecret;

    /** @var string */
    private $baseUrl;

    /** @var HttpTransport */
    private $transport;

    /** @var string */
    private $token = '';

    /** @var int */
    private $expireAt = 0;

    /** @var TokenManager|null */
    private $tokenManager = null;

    /**
     * __construct 初始化应用凭证
     * @param string $appId 应用 app_id
     * @param string $appSecret 应用 app_secret
     * @param string $baseUrl OpenAPI 根地址
     * @param HttpTransport|null $transport HTTP 传输
     * @param TokenManager|null $tokenManager 可选的 TokenManager（优先使用）
     */
    public function __construct(string $appId, string $appSecret, string $baseUrl = 'https://open.feishu.cn/open-apis', ?HttpTransport $transport = null, ?TokenManager $tokenManager = null)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->transport = $transport ?: new HttpTransport();
        $this->tokenManager = $tokenManager;
    }

    /**
     * getTenantAccessToken 获取 tenant_access_token（带进程内缓存或通过 TokenManager）
     * @return string
     */
    public function getTenantAccessToken()
    {
        if ($this->tokenManager !== null) {
            return $this->tokenManager->getTenantAccessToken();
        }

        if ($this->token !== '' && $this->expireAt > time() + 60) {
            return $this->token;
        }

        $url = $this->baseUrl . '/auth/v3/tenant_access_token/internal';
        $body = json_encode([
            'app_id' => $this->appId,
            'app_secret' => $this->appSecret,
        ], JSON_UNESCAPED_UNICODE);

        $raw = $this->transport->request('POST', $url, [
            'Content-Type: application/json; charset=utf-8',
        ], $body);

        $result = json_decode($raw, true);
        if (!is_array($result) || (int)($result['code'] ?? -1) !== 0) {
            throw new FeishuException(
                '获取 tenant_access_token 失败：' . ($result['msg'] ?? $raw),
                (int)($result['code'] ?? 0),
                $raw
            );
        }

        $this->token = (string)($result['tenant_access_token'] ?? '');
        $expire = (int)($result['expire'] ?? 7200);
        $this->expireAt = time() + max($expire - 120, 60);

        if ($this->token === '') {
            throw new FeishuException('获取 tenant_access_token 失败：token 为空', 0, $raw);
        }

        return $this->token;
    }
}
