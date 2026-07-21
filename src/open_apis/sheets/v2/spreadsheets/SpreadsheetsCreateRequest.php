<?php

declare(strict_types=1);

namespace feishu\open_apis\sheets\v2\spreadsheets;

use feishu\Contract\RequestInterface;

/**
 * SpreadsheetsCreateRequest 创建表格示例请求
 */
class SpreadsheetsCreateRequest implements RequestInterface
{
    private array $body;

    public function __construct(array $body)
    {
        $this->body = $body;
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function getPath(): string
    {
        return '/sheets/v2/spreadsheets';
    }

    public function getQuery(): array
    {
        return [];
    }

    public function getBody(): ?array
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return [];
    }
}
