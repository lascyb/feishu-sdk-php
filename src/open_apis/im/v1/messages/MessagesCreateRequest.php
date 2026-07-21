<?php

declare(strict_types=1);

namespace feishu\open_apis\im\v1\messages;

use feishu\Contract\RequestInterface;

/**
 * MessagesCreateRequest 发送消息示例请求封装
 */
class MessagesCreateRequest implements RequestInterface
{
    private array $body;
    private array $query = [];
    private array $headers = [];

    /**
     * @param array $body 消息体，例如 [ 'receive_id' => 'oc_xxx', 'msg_type' => 'text', 'content' => json_encode(['text'=>'hi'], JSON_UNESCAPED_UNICODE) ]
     * @param array $query 可选查询参数（如 receive_id_type）
     */
    public function __construct(array $body, array $query = [], array $headers = [])
    {
        $this->body = $body;
        $this->query = $query;
        $this->headers = $headers;
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function getPath(): string
    {
        return '/im/v1/messages';
    }

    public function getQuery(): array
    {
        return $this->query;
    }

    public function getBody(): ?array
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
