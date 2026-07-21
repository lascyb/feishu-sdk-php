<?php

declare(strict_types=1);

namespace feishu\open_apis\drive\v1\files;

use feishu\Contract\RequestInterface;

/**
 * FilesUploadRequest 占位：文件上传通常为 multipart/form-data 并通过独立上传接口处理
 * 本类提供元数据创建 / 升级接口封装示例。实际上传请使用 Drive 上传流程辅助方法。
 */
class FilesUploadRequest implements RequestInterface
{
    private array $body;
    private array $query = [];

    public function __construct(array $body, array $query = [])
    {
        $this->body = $body;
        $this->query = $query;
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    public function getPath(): string
    {
        return '/drive/v1/files';
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
        // For file upload, transport layer may need to set Content-Type multipart/form-data
        return [];
    }
}
