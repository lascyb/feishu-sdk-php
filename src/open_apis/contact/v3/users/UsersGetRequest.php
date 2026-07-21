<?php

declare(strict_types=1);

namespace feishu\open_apis\contact\v3\users;

use feishu\Contract\RequestInterface;

/**
 * UsersGetRequest 获取用户示例请求（通过 user_id / open_id / email）
 */
class UsersGetRequest implements RequestInterface
{
    private string $userId;
    private array $query = [];

    public function __construct(string $userId, array $query = [])
    {
        $this->userId = $userId;
        $this->query = $query;
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function getPath(): string
    {
        return '/contact/v3/users/' . urlencode($this->userId);
    }

    public function getQuery(): array
    {
        return $this->query;
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
