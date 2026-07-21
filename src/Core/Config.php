<?php

namespace feishu\Core;

class Config
{
    public string $appId;
    public string $appSecret;
    public string $baseUrl;
    public int $requestTimeout = 10;

    public function __construct(string $appId, string $appSecret, string $baseUrl = 'https://open.feishu.cn/open-apis')
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->baseUrl = rtrim($baseUrl, '/');
    }
}
