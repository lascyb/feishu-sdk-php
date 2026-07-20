<?php

namespace feishu\Contract;

/**
 * RequestInterface 飞书 OpenAPI 请求描述（方法、路径、参数由各实现类提供）
 */
interface RequestInterface
{
    /**
     * getMethod HTTP 方法
     * @return string
     */
    public function getMethod();

    /**
     * getPath API 路径（相对 open-apis 根路径，以 / 开头）
     * @return string
     */
    public function getPath();

    /**
     * getQuery 查询参数
     * @return array
     */
    public function getQuery();

    /**
     * getBody 请求体（JSON 接口返回数组，由 Client 序列化）
     * @return array|null
     */
    public function getBody();

    /**
     * getHeaders 额外请求头（不含 Authorization、Content-Type）
     * @return array
     */
    public function getHeaders();
}
