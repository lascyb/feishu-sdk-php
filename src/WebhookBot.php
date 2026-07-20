<?php

namespace feishu;

/**
 * WebhookBot 飞书群自定义机器人 Webhook 消息推送
 * 文档：https://open.feishu.cn/document/client-docs/bot-v3/add-custom-bot
 *
 * 安全设置说明：
 * - 签名校验：构造时传入 $secret，发送时自动附加 timestamp / sign
 * - 自定义关键词：调用 setKeywords() 后，发送前会校验消息 text/title 是否至少命中一个关键词
 * - IP 白名单：需在飞书机器人后台配置，SDK 侧无法代为校验
 */
class WebhookBot
{
    private const MAX_BODY_BYTES = 20480; // 请求体上限 20 KB

    /** @var string */
    private $webhookUrl;

    /** @var string 签名校验密钥，空表示未开启 */
    private $secret;

    /** @var string[] 自定义关键词（客户端预校验，与飞书后台配置保持一致） */
    private $keywords = [];

    /** @var bool 是否强制要求已配置签名密钥 */
    private $requireSign = false;

    /** @var string */
    private $lastRawResponse = '';

    /** @var string */
    private $lastCurlError = '';

    /**
     * __construct 初始化 Webhook 地址与签名密钥
     * @param string $webhookUrl 机器人 Webhook 完整地址
     * @param string $secret 签名校验密钥（飞书后台开启签名校验时必填）
     */
    public function __construct($webhookUrl, $secret = '')
    {
        $this->webhookUrl = trim((string)$webhookUrl);
        $this->secret = (string)$secret;
    }

    /**
     * setKeywords 设置自定义关键词（发送前本地预校验，需与飞书后台配置一致）
     * @param string[] $keywords 关键词列表，空数组表示关闭预校验
     * @return $this
     */
    public function setKeywords(array $keywords)
    {
        $normalized = [];
        foreach ($keywords as $keyword) {
            $keyword = trim((string)$keyword);
            if ($keyword !== '') {
                $normalized[] = $keyword;
            }
        }
        $this->keywords = $normalized;

        return $this;
    }

    /**
     * requireSign 是否强制要求签名密钥（开启后未配置 secret 将拒绝发送）
     * @param bool $require
     * @return $this
     */
    public function requireSign($require = true)
    {
        $this->requireSign = (bool)$require;

        return $this;
    }

    /**
     * sendText 发送文本消息
     * @param string $text 消息正文（可用 atUser / atAll 拼接 @）
     * @return array 接口响应
     */
    public function sendText($text)
    {
        return $this->send([
            'msg_type' => 'text',
            'content' => [
                'text' => (string)$text,
            ],
        ]);
    }

    /**
     * sendPost 发送富文本消息
     * @param array $post 富文本结构，如 ['zh_cn' => ['title' => '...', 'content' => [...]]]
     * @return array 接口响应
     */
    public function sendPost(array $post)
    {
        return $this->send([
            'msg_type' => 'post',
            'content' => [
                'post' => $post,
            ],
        ]);
    }

    /**
     * sendShareChat 发送群名片（仅限机器人所在群）
     * @param string $shareChatId 群 ID（oc_xxx）
     * @return array 接口响应
     */
    public function sendShareChat($shareChatId)
    {
        return $this->send([
            'msg_type' => 'share_chat',
            'content' => [
                'share_chat_id' => (string)$shareChatId,
            ],
        ]);
    }

    /**
     * sendImage 发送图片消息
     * @param string $imageKey 图片 key（需先通过上传图片接口获取）
     * @return array 接口响应
     */
    public function sendImage($imageKey)
    {
        return $this->send([
            'msg_type' => 'image',
            'content' => [
                'image_key' => (string)$imageKey,
            ],
        ]);
    }

    /**
     * sendCard 发送飞书卡片（卡片 JSON，msg_type=interactive）
     * @param array $card 卡片结构体（schema 2.0 或旧版 elements 等）
     * @return array 接口响应
     */
    public function sendCard(array $card)
    {
        return $this->send([
            'msg_type' => 'interactive',
            'card' => $card,
        ]);
    }

    /**
     * sendCardTemplate 发送搭建工具发布的卡片模板
     * @param string $templateId 卡片模板 ID
     * @param array $templateVariable 模板变量键值对
     * @param string $version 模板版本号，空则使用最新版本
     * @return array 接口响应
     */
    public function sendCardTemplate($templateId, array $templateVariable = [], $version = '')
    {
        $data = [
            'template_id' => (string)$templateId,
            'template_variable' => $templateVariable,
        ];
        if ($version !== '') {
            $data['template_version_name'] = (string)$version;
        }

        return $this->send([
            'msg_type' => 'interactive',
            'card' => [
                'type' => 'template',
                'data' => $data,
            ],
        ]);
    }

    /**
     * atUser 生成文本消息中的 @ 指定人标签
     * @param string $userId open_id 或 user_id
     * @param string $name 展示名
     * @return string
     */
    public static function atUser($userId, $name = '')
    {
        return '<at user_id="' . (string)$userId . '">' . (string)$name . '</at>';
    }

    /**
     * atAll 生成文本消息中的 @ 所有人标签
     * @param string $name 展示名
     * @return string
     */
    public static function atAll($name = '所有人')
    {
        return self::atUser('all', $name);
    }

    /**
     * send 发送自定义消息体（自动附加签名，并执行安全预校验）
     * @param array $body 消息体（需含 msg_type 等字段）
     * @return array 接口响应
     */
    public function send(array $body)
    {
        if ($this->webhookUrl === '') {
            throw new \InvalidArgumentException('飞书 Webhook 地址未配置');
        }

        if ($this->requireSign && $this->secret === '') {
            throw new \InvalidArgumentException('已开启 requireSign，但未配置签名密钥');
        }

        $this->assertKeywords($body);

        $payload = $this->attachSign($body);
        $encoded = json_encode($payload, JSON_UNESCAPED_UNICODE);
        if ($encoded === false) {
            throw new FeishuException('消息体 JSON 编码失败');
        }
        if (strlen($encoded) > self::MAX_BODY_BYTES) {
            throw new FeishuException('请求体超过 20 KB 限制');
        }

        $response = $this->post($encoded);

        if (!$this->isSuccess($response)) {
            throw new FeishuException(
                '飞书消息发送失败：' . $this->formatError($response),
                (int)($response['code'] ?? $response['StatusCode'] ?? 0),
                $this->lastRawResponse
            );
        }

        return $response;
    }

    /**
     * attachSign 为请求体附加 timestamp、sign（开启签名校验时）
     * @param array $body 原始消息体
     * @return array
     */
    public function attachSign(array $body)
    {
        if ($this->secret === '') {
            return $body;
        }

        $timestamp = (string)time();
        $body['timestamp'] = $timestamp;
        $body['sign'] = self::genSign($timestamp, $this->secret);

        return $body;
    }

    /**
     * genSign 计算签名字符串（timestamp + "\\n" + secret 作为 HMAC key，对空串做 HmacSHA256 再 Base64）
     * @param string|int $timestamp 时间戳（秒），距当前不超过 1 小时
     * @param string $secret 签名密钥
     * @return string
     */
    public static function genSign($timestamp, $secret)
    {
        $stringToSign = $timestamp . "\n" . $secret;

        return base64_encode(hash_hmac('sha256', '', $stringToSign, true));
    }

    /**
     * isSuccess 判断 Webhook 响应是否成功
     * @param array $response 接口响应
     * @return bool
     */
    public function isSuccess(array $response)
    {
        if (isset($response['code']) && (int)$response['code'] === 0) {
            return true;
        }
        if (isset($response['StatusCode']) && (int)$response['StatusCode'] === 0) {
            return true;
        }

        return false;
    }

    /**
     * getLastRawResponse 获取最后一次原始响应
     * @return string
     */
    public function getLastRawResponse()
    {
        return $this->lastRawResponse;
    }

    /**
     * assertKeywords 自定义关键词预校验（仅检查 text / title 类文本，与飞书规则一致）
     * @param array $body 消息体
     * @return void
     */
    private function assertKeywords(array $body)
    {
        if ($this->keywords === []) {
            return;
        }

        $haystack = $this->collectKeywordText($body);
        foreach ($this->keywords as $keyword) {
            if (function_exists('mb_strpos')) {
                if (mb_strpos($haystack, $keyword) !== false) {
                    return;
                }
            } elseif (strpos($haystack, $keyword) !== false) {
                return;
            }
        }

        throw new FeishuException('自定义关键词校验失败：消息未包含配置的关键词', 19024);
    }

    /**
     * collectKeywordText 提取可用于关键词匹配的文本（text / title）
     * @param mixed $value
     * @return string
     */
    private function collectKeywordText($value)
    {
        if (!is_array($value)) {
            return '';
        }

        $parts = [];
        foreach ($value as $key => $item) {
            if (($key === 'text' || $key === 'title') && (is_string($item) || is_numeric($item))) {
                $parts[] = (string)$item;
                continue;
            }
            if (is_array($item)) {
                $nested = $this->collectKeywordText($item);
                if ($nested !== '') {
                    $parts[] = $nested;
                }
            }
        }

        return implode("\n", $parts);
    }

    /**
     * post 发起 POST 请求
     * @param string $jsonBody 已编码的 JSON 请求体
     * @return array
     */
    private function post($jsonBody)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->webhookUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json; charset=utf-8']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $res = curl_exec($ch);
        $this->lastRawResponse = $res === false ? '' : (string)$res;

        if ($res === false) {
            $this->lastCurlError = curl_error($ch);
            unset($ch);
            throw new FeishuException('飞书 Webhook 请求失败：' . $this->lastCurlError);
        }

        unset($ch);

        $result = json_decode($res, true);

        return is_array($result) ? $result : [];
    }

    /**
     * formatError 格式化错误信息
     * @param array $response 接口响应
     * @return string
     */
    private function formatError(array $response)
    {
        if (empty($response)) {
            return $this->lastRawResponse !== '' ? $this->lastRawResponse : '响应为空或非法 JSON';
        }

        $parts = [];
        foreach (['code', 'msg', 'StatusCode', 'StatusMessage'] as $key) {
            if (isset($response[$key]) && $response[$key] !== '') {
                $parts[] = $key . '=' . $response[$key];
            }
        }

        return $parts ? implode('，', $parts) : json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}
