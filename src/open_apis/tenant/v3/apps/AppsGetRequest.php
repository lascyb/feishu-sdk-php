<?php

declare(strict_types=1);

namespace feishu\open_apis\tenant\v3\apps;

use feishu\Contract\RequestInterface;

/**
 * AppsGetRequest 获取应用信息示例
 */
class AppsGetRequest implements RequestInterface
{
    private string $appId;

    public function __construct(string $appId)
    {
        $this->appId = $appId;
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function getPath(): string
    {
        return '/tenant/v3/apps/' . urlencode($this->appId);
    }

    public function getQuery(): array
    {
        return [];
    }

    public function getBody(): ?array
    {
        return null;
    }

    public function getHeaders(): array
    {
        return [];
    }
}
