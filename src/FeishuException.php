<?php

namespace feishu;

use RuntimeException;

/**
 * FeishuException 飞书 OpenAPI 调用异常
 */
class FeishuException extends RuntimeException
{
    /** @var int */
    private $apiCode;

    /** @var string */
    private $rawResponse;

    /**
     * __construct 构造异常
     * @param string $message 错误说明
     * @param int $apiCode 飞书 code
     * @param string $rawResponse 原始响应
     */
    public function __construct($message, $apiCode = 0, $rawResponse = '')
    {
        parent::__construct($message);
        $this->apiCode = (int)$apiCode;
        $this->rawResponse = (string)$rawResponse;
    }

    /**
     * getApiCode 获取飞书错误码
     * @return int
     */
    public function getApiCode()
    {
        return $this->apiCode;
    }

    /**
     * getRawResponse 获取原始响应
     * @return string
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }
}
