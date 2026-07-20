<?php

namespace feishu;

/**
 * HttpTransport 飞书 OpenAPI HTTP 传输
 */
class HttpTransport
{
    /** @var string */
    private $lastRawResponse = '';

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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        if ($body !== null && $body !== '') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $res = curl_exec($ch);
        $this->lastRawResponse = $res === false ? '' : (string)$res;

        if ($res === false) {
            $error = curl_error($ch);
            unset($ch);
            throw new FeishuException('HTTP 请求失败：' . $error);
        }

        unset($ch);

        return $this->lastRawResponse;
    }
}
