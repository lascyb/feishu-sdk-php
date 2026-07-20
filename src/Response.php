<?php

namespace feishu;

/**
 * Response 飞书 OpenAPI 响应
 */
class Response
{
    /** @var array */
    private $payload;

    /**
     * __construct 包装解码后的响应数组
     * @param array $payload 响应 JSON
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * isOk 是否成功（code=0）
     * @return bool
     */
    public function isOk()
    {
        return isset($this->payload['code']) && (int)$this->payload['code'] === 0;
    }

    /**
     * code 获取业务错误码
     * @return int
     */
    public function code()
    {
        return (int)($this->payload['code'] ?? -1);
    }

    /**
     * msg 获取错误描述
     * @return string
     */
    public function msg()
    {
        return (string)($this->payload['msg'] ?? '');
    }

    /**
     * data 获取 data 节点或其中字段
     * @param string|null $key 字段名，空则返回整个 data
     * @return mixed
     */
    public function data($key = null)
    {
        $data = $this->payload['data'] ?? [];
        if ($key === null || $key === '') {
            return $data;
        }

        return is_array($data) && array_key_exists($key, $data) ? $data[$key] : null;
    }

    /**
     * toArray 获取完整响应数组
     * @return array
     */
    public function toArray()
    {
        return $this->payload;
    }
}
