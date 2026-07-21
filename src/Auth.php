<?php

/*
 * Deprecated compatibility shim.
 * The Auth functionality has been moved to feishu\Core\TokenManager.
 * This class remains to preserve backward compatibility for a short period.
 */

namespace feishu;

use feishu\Core\Config as CoreConfig;
use feishu\Core\TokenManager;
use Psr\SimpleCache\CacheInterface;

class Auth
{
    private ?TokenManager $tokenManager = null;

    public function __construct(string $appId, string $appSecret, string $baseUrl = 'https://open.feishu.cn/open-apis', ?HttpTransport $transport = null, ?CacheInterface $cache = null)
    {
        $config = new CoreConfig($appId, $appSecret, $baseUrl);
        $this->tokenManager = new TokenManager($config, $transport ?: new HttpTransport(), $cache);
    }

    /**
     * Deprecated: use TokenManager->getTenantAccessToken() instead.
     */
    public function getTenantAccessToken()
    {
        trigger_error('feishu\\Auth is deprecated, use feishu\\Core\\TokenManager instead', E_USER_DEPRECATED);
        return $this->tokenManager->getTenantAccessToken();
    }
}
