<?php

namespace feishu\Channel;

use feishu\Core\Config;
use feishu\Core\TokenManager;
use feishu\Channel\WebSocketClient;

/**
 * Channel 客户端（长连接管理器）
 *
 * 负责建立连接、接收事件并通过回调分发。
 */
class Client
{
    private Config $config;
    private TokenManager $tokenManager;
    private ?WebSocketClient $ws = null;
    private $eventCallback = null;
    private int $timeout = 30;

    public function __construct(Config $config, TokenManager $tm)
    {
        $this->config = $config;
        $this->tokenManager = $tm;
    }

    /**
     * 注入事件回调，回调接收一个归一化后的事件数组
     */
    public function onEvent(callable $cb): void
    {
        $this->eventCallback = $cb;
    }

    /**
     * 建立 WebSocket 连接并开始事件循环（阻塞）
     * 如果没有安装 `textalk/websocket` 将抛出异常。
     */
    public function connect(string $wsUrl = ''): void
    {
        if ($wsUrl === '') {
            // 默认 Channel URL（可根据需要从配置或服务端查询）
            $wsUrl = 'wss://open.feishu.cn/channel/ws';
        }

        // 可以在这里使用 tenant_access_token 或其他方式完成连接鉴权
        $token = $this->tokenManager->getTenantAccessToken();

        $this->ws = new WebSocketClient($wsUrl, [
            'timeout' => $this->timeout,
            'headers' => [
                'Authorization: Bearer ' . $token,
            ],
        ]);

        // 简单循环：阻塞接收信息并调用回调
        while (true) {
            $msg = $this->ws->receive();
            if ($msg === null) {
                // 连接关闭或超时，尝试重连或退出
                break;
            }

            $event = $this->normalizeEvent($msg);
            if ($this->eventCallback !== null) {
                try {
                    call_user_func($this->eventCallback, $event);
                } catch (\Throwable $e) {
                    // 捕获回调异常，继续循环
                }
            }
        }
    }

    /**
     * 发送消息（封装）
     */
    public function send(array $payload): void
    {
        if ($this->ws === null) {
            throw new \RuntimeException('WebSocket 未连接');
        }

        $this->ws->send(json_encode($payload, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 关闭连接
     */
    public function close(): void
    {
        if ($this->ws !== null) {
            $this->ws->close();
            $this->ws = null;
        }
    }

    /**
     * 原始消息归一化（占位实现）
     */
    private function normalizeEvent(string $raw): array
    {
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            return ['type' => 'unknown', 'raw' => $raw];
        }

        // TODO: 根据飞书 Channel 原始事件结构实现详细归一化
        return $data;
    }
}
