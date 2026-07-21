<?php

namespace feishu;

use feishu\Contract\RequestInterface;

/**
 * AnyRequest 任意 OpenAPI 请求（未封装的接口可直接用本类调用）
 */
class AnyRequest implements RequestInterface
{
    /** @var string HTTP 方法 */
    private string $method;

    /** @var string API 路径（相对 open-apis，以 / 开头） */
    private string $path;

    /** @var array 查询参数 */
    private array $query;

    /** @var array|null 请求体 */
    private ?array $body;

    /** @var array 额外请求头 */
    private array $headers;

    /**
     * __construct 构造任意接口请求
     * @param string $method HTTP 方法（GET/POST/PUT/PATCH/DELETE 等）
     * @param string $path API 路径，如 /im/v1/messages
     * @param array $query 查询参数
     * @param array|null $body 请求体（无 body 传 null）
     * @param array $headers 额外请求头（不含 Authorization、Content-Type）
     */
    public function __construct(
        string $method,
        string $path,
        array $query = [],
        ?array $body = null,
        array $headers = []
    ) {
        $method = strtoupper(trim($method));
        if ($method === '') {
            throw new \InvalidArgumentException('HTTP 方法不能为空');
        }

        $path = '/' . ltrim(trim($path), '/');
        if ($path === '/') {
            throw new \InvalidArgumentException('API 路径不能为空');
        }

        $this->method = $method;
        $this->path = $path;
        $this->query = $query;
        $this->body = $body;
        $this->headers = $headers;
    }

    /**
     * get 快捷构造 GET 请求
     * @param string $path API 路径
     * @param array $query 查询参数
     * @param array $headers 额外请求头
     */
    public static function get(string $path, array $query = [], array $headers = []): self
    {
        return new self('GET', $path, $query, null, $headers);
    }

    /**
     * post 快捷构造 POST 请求
     * @param string $path API 路径
     * @param array|null $body 请求体
     * @param array $query 查询参数
     * @param array $headers 额外请求头
     */
    public static function post(string $path, ?array $body = null, array $query = [], array $headers = []): self
    {
        return new self('POST', $path, $query, $body, $headers);
    }

    /**
     * put 快捷构造 PUT 请求
     * @param string $path API 路径
     * @param array|null $body 请求体
     * @param array $query 查询参数
     * @param array $headers 额外请求头
     */
    public static function put(string $path, ?array $body = null, array $query = [], array $headers = []): self
    {
        return new self('PUT', $path, $query, $body, $headers);
    }

    /**
     * patch 快捷构造 PATCH 请求
     * @param string $path API 路径
     * @param array|null $body 请求体
     * @param array $query 查询参数
     * @param array $headers 额外请求头
     */
    public static function patch(string $path, ?array $body = null, array $query = [], array $headers = []): self
    {
        return new self('PATCH', $path, $query, $body, $headers);
    }

    /**
     * delete 快捷构造 DELETE 请求
     * @param string $path API 路径
     * @param array $query 查询参数
     * @param array|null $body 请求体
     * @param array $headers 额外请求头
     */
    public static function delete(string $path, array $query = [], ?array $body = null, array $headers = []): self
    {
        return new self('DELETE', $path, $query, $body, $headers);
    }

    /**
     * getMethod HTTP 方法
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * getPath API 路径
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * getQuery 查询参数
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * getBody 请求体
     */
    public function getBody(): ?array
    {
        return $this->body;
    }

    /**
     * getHeaders 额外请求头
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
