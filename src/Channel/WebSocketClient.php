<?php

namespace feishu\Channel;

/**
 * WebSocketClient: 使用可用的 PHP WebSocket 客户端库（textalk/websocket）进行包装。
 *
 * 这是一个非常轻量的封装，提供 send / receive / close 接口。
 */
class WebSocketClient
{
    private $client;

    /**
     * @param string $url
     * @param array $options 例如 ['timeout'=>30, 'headers'=>[]]
     */
    public function __construct(string $url, array $options = [])
    {
        if (!class_exists('WebSocket\Client')) {
            throw new \RuntimeException('缺少 WebSocket 客户端依赖，请安装 textalk/websocket: composer require textalk/websocket');
        }

        $headers = $options['headers'] ?? [];
        $timeout = $options['timeout'] ?? 30;

        // WebSocket\Client 构造器示例： new WebSocket\Client($url, $options)
        $opts = ['timeout' => $timeout, 'headers' => $headers];
        $this->client = new \WebSocket\Client($url, $opts);
    }

    public function send(string $data): void
    {
        $this->client->send($data);
    }

    public function receive(): ?string
    {
        try {
            $msg = $this->client->receive();
            return $msg;
        } catch (\WebSocket\ConnectionException $e) {
            // 连接异常
            return null;
        }
    }

    public function close(): void
    {
        $this->client->close();
    }
}
