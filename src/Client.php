<?php

namespace feishu;

use feishu\Contract\RequestInterface;
use feishu\Core\Config as CoreConfig;
use feishu\Core\TokenManager;

/**
 * Client 飞书 OpenAPI 客户端（统一 request 入口，HTTP 方法由 Request 定义）
 *
 * 兼容构造方式：
 * - new Client($appId, $appSecret, $baseUrl = null)
 * - new Client($coreConfig)
 */
class Client
{
    /** @var TokenManager */
    private $tokenManager;

    /** @var HttpTransport */
    private $transport;

    /** @var string */
    private $baseUrl;

    /**
     * __construct 初始化客户端
     * @param mixed $appIdOrConfig 应用 app_id 或 Core\Config 实例
     * @param string|null $appSecret 应用 app_secret（当传入 Core\Config 时可省略）
     * @param string $baseUrl OpenAPI 根地址
     */
    public function __construct($appIdOrConfig, $appSecret = null, $baseUrl = 'https://open.feishu.cn/open-apis')
    {
        $this->transport = new HttpTransport();

        if (is_object($appIdOrConfig) && $appIdOrConfig instanceof CoreConfig) {
            $config = $appIdOrConfig;
        } else {
            $config = new CoreConfig((string)$appIdOrConfig, (string)$appSecret, $baseUrl);
        }

        $this->baseUrl = $config->baseUrl;
        $this->tokenManager = new TokenManager($config, $this->transport);
    }

    /**
     * Allow injection of a custom TokenManager (e.g. with Redis cache).
     */
    public function setTokenManager(TokenManager $tm): void
    {
        $this->tokenManager = $tm;
    }

    /**
     * request 执行飞书 OpenAPI 请求（使用 tenant_access_token）
     * @param RequestInterface $request 请求对象
     * @return Response
     */
    public function request(RequestInterface $request)
    {
        return $this->doRequest($request, $this->tokenManager->getTenantAccessToken());
    }

    /**
     * requestWithUserAccessToken 使用 user_access_token 执行请求（如获取登录用户信息）
     * @param RequestInterface $request 请求对象
     * @param string $userAccessToken 用户 access_token
     * @return Response
     */
    public function requestWithUserAccessToken(RequestInterface $request, string $userAccessToken)
    {
        $token = trim($userAccessToken);
        if ($token === '') {
            throw new \InvalidArgumentException('user_access_token 不能为空');
        }

        return $this->doRequest($request, $token);
    }

    /**
     * doRequest 携带指定 Bearer token 执行请求
     * @param RequestInterface $request 请求对象
     * @param string $accessToken access_token
     * @return Response
     */
    private function doRequest(RequestInterface $request, string $accessToken)
    {
        $url = $this->buildUrl($request->getPath(), $request->getQuery());

        $headers = array_merge([
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json; charset=utf-8',
        ], $this->formatHeaders($request->getHeaders()));

        $body = $request->getBody();
        $bodyString = $body === null ? null : json_encode($body, JSON_UNESCAPED_UNICODE);

        $raw = $this->transport->request($request->getMethod(), $url, $headers, $bodyString);
        $payload = json_decode($raw, true);

        if (!is_array($payload)) {
            throw new FeishuException('响应不是合法 JSON', 0, $raw);
        }

        $response = new Response($payload);
        if (!$response->isOk()) {
            throw new FeishuException(
                '飞书接口调用失败：' . $response->msg(),
                $response->code(),
                $raw
            );
        }

        return $response;
    }

    /**
     * buildUrl 拼接请求 URL
     * @param string $path API 路径
     * @param array $query 查询参数
     * @return string
     */
    private function buildUrl($path, array $query)
    {
        $url = rtrim($this->baseUrl, '/') . $path;
        if (empty($query)) {
            return $url;
        }

        return $url . '?' . http_build_query($query);
    }

    /**
     * formatHeaders 格式化额外请求头
     * @param array $headers
     * @return array
     */
    private function formatHeaders(array $headers)
    {
        $formatted = [];
        foreach ($headers as $key => $value) {
            if (is_int($key)) {
                $formatted[] = (string)$value;
                continue;
            }
            $formatted[] = $key . ': ' . $value;
        }

        return $formatted;
    }
}
