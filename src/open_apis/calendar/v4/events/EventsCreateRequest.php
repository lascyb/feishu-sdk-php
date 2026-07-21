<?php

declare(strict_types=1);

namespace feishu\open_apis\calendar\v4\events;

use feishu\Contract\RequestInterface;

/**
 * EventsCreateRequest 日历事件创建请求示例
 */
class EventsCreateRequest implements RequestInterface
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
        return '/calendar/v4/events';
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
