<?php

namespace feishu;

use feishu\Core\TokenManager;

/**
 * OAuth2 飞书网页应用授权登录（authorization_code / refresh_token / PKCE）
 * 文档：https://open.feishu.cn/document/common-capabilities/sso/api/obtain-oauth-code
 */
class OAuth2
{
    /** @var string */
    private $clientId;

    /** @var string */
    private $clientSecret;

    /** @var string */
    private $redirectUri;

    /** @var string */
    private $authUrl;

    /** @var string */
    private $tokenUrl;

    /** @var HttpTransport */
    private $transport;

    /** @var TokenManager|null */
    private $tokenManager = null;

    /**
     * __construct 初始化 OAuth2 客户端
     * @param string $clientId 应用 app_id
     * @param string $clientSecret 应用 app_secret
     * @param string $redirectUri 授权回调地址
     * @param string $authUrl 授权页地址
     * @param string $tokenUrl 换 token 地址
     * @param HttpTransport|null $transport HTTP 传输
     * @param TokenManager|null $tokenManager 可选注入 TokenManager
     */
    public function __construct(
        string $clientId,
        string $clientSecret,
        string $redirectUri,
        string $authUrl = 'https://open.feishu.cn/open-apis/authen/v1/authorize',
        string $tokenUrl = 'https://open.feishu.cn/open-apis/authen/v2/oauth/token',
        ?HttpTransport $transport = null,
        ?TokenManager $tokenManager = null
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->authUrl = $authUrl;
        $this->tokenUrl = $tokenUrl;
        $this->transport = $transport ?: new HttpTransport();
        $this->tokenManager = $tokenManager;
    }

    /**
     * authorize 生成授权 URL
     * @param array $scope 权限范围
     * @param string $state CSRF 防护 state
     * @param bool $redirectUser 是否直接 302 跳转并结束进程
     * @param string|null $codeVerifier 非空时启用 PKCE（S256）
     * @return string 授权 URL
     */
    public function authorize(array $scope = [], string $state = '', bool $redirectUser = false, ?string $codeVerifier = null): string
    {
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'server' => 1,
        ];

        if (!empty($scope)) {
            $params['scope'] = implode(' ', $scope);
        }

        if ($state !== '') {
            $params['state'] = $state;
        }

        if ($codeVerifier !== null) {
            $params['code_challenge'] = $this->generateCodeChallenge($codeVerifier);
            $params['code_challenge_method'] = 'S256';
        }

        $authUrl = $this->authUrl . '?' . http_build_query($params);

        if ($redirectUser) {
            header('Location: ' . $authUrl);
            exit;
        }

        return $authUrl;
    }

    /**
     * getAccessToken 用授权码换取 user_access_token
     * @param string $code 授权码
     * @param string|null $codeVerifier 启用 PKCE 时传入同一 code_verifier
     * @return array 令牌响应（含 code / access_token / refresh_token 等）
     */
    public function getAccessToken(string $code, ?string $codeVerifier = null): array
    {
        $params = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
        ];

        if ($codeVerifier !== null) {
            $params['code_verifier'] = $codeVerifier;
        }

        return $this->requestToken($params);
    }

    /**
     * refreshAccessToken 用 refresh_token 刷新 access_token
     * @param string $refreshToken 刷新令牌
     * @param array|null $scope 可选缩小权限范围
     * @return array 令牌响应
     */
    public function refreshAccessToken(string $refreshToken, ?array $scope = null): array
    {
        $params = [
            'grant_type' => 'refresh_token',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $refreshToken,
        ];

        if (!empty($scope)) {
            $params['scope'] = implode(' ', $scope);
        }

        return $this->requestToken($params);
    }

    /**
     * generateCodeVerifier 生成 PKCE code_verifier
     * @param int $length 长度，默认 64
     * @return string
     */
    public function generateCodeVerifier(int $length = 64): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-._~';
        $charLength = strlen($chars) - 1;
        $verifier = '';

        for ($i = 0; $i < $length; $i++) {
            $verifier .= $chars[random_int(0, $charLength)];
        }

        return $verifier;
    }

    /**
     * requestToken 请求 token 接口
     * @param array $params 请求体
     * @return array
     */
    private function requestToken(array $params): array
    {
        $raw = $this->transport->request(
            'POST',
            $this->tokenUrl,
            ['Content-Type: application/json; charset=utf-8'],
            json_encode($params, JSON_UNESCAPED_UNICODE)
        );

        $result = json_decode($raw, true);
        if (!is_array($result)) {
            throw new FeishuException('OAuth2 响应不是合法 JSON', 0, $raw);
        }

        return $result;
    }

    /**
     * generateCodeChallenge 由 code_verifier 生成 S256 code_challenge
     * @param string $codeVerifier
     * @return string
     */
    private function generateCodeChallenge(string $codeVerifier): string
    {
        $hash = hash('sha256', $codeVerifier, true);

        return rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
    }

    /**
     * 注入 TokenManager（可选）
     */
    public function setTokenManager(TokenManager $tm): void
    {
        $this->tokenManager = $tm;
    }
}
