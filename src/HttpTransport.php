<?php

namespace feishu;

/**
 * HttpTransport 飞书 OpenAPI HTTP 传输
 */
class HttpTransport
{
    /** @var string */
    private $lastRawResponse = '';

    /** @var int 连接超时（秒） */
    private $connectTimeout = 30;

    /** @var int 总超时（秒） */
    private $timeout = 60;

    /** @var int 可重试错误的最大尝试次数 */
    private $maxAttempts = 3;

    /**
     * getLastRawResponse 获取最后一次原始响应
     * @return string
     */
    public function getLastRawResponse()
    {
        return $this->lastRawResponse;
    }

    /**
     * request 发起 HTTP 请求
     * @param string $method HTTP 方法
     * @param string $url 完整 URL
     * @param array $headers 请求头 ['Name: value']
     * @param string|null $body 请求体 JSON 字符串
     * @return string
     */
    public function request($method, $url, array $headers = [], $body = null)
    {
        $lastError = '';
        for ($attempt = 1; $attempt <= $this->maxAttempts; $attempt++) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

            if ($body !== null && $body !== '') {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }

            $res = curl_exec($ch);
            $this->lastRawResponse = $res === false ? '' : (string) $res;

            if ($res !== false) {
                unset($ch);

                return $this->lastRawResponse;
            }

            $lastError = curl_error($ch);
            unset($ch);

            if ($attempt >= $this->maxAttempts || !$this->isRetryableTransportError($lastError)) {
                break;
            }

            usleep(500000 * $attempt);
        }

        throw new FeishuException('HTTP 请求失败：' . $lastError);
    }

    /**
     * isRetryableTransportError DNS/连接类瞬时错误可重试
     */
    private function isRetryableTransportError(string $error): bool
    {
        $lower = strtolower($error);

        return str_contains($lower, 'timed out')
            || str_contains($lower, 'resolving')
            || str_contains($lower, 'could not resolve')
            || str_contains($lower, 'connection refused')
            || str_contains($lower, 'connection reset');
    }
}
