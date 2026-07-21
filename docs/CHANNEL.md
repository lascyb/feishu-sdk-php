# Channel 模块（长连接 & 事件处理）

本文件说明如何使用 SDK 提供的 Channel 功能以建立 WebSocket 长连接、接收事件和发送消息。现阶段实现为可用的骨架（scaffolding），并提供对常用 PHP WebSocket 客户端库 `textalk/websocket` 的集成示例。

## 概要

Channel 模块负责：

- 建立并维护与飞书 Channel 服务的长连接（WebSocket）。
- 把原始事件归一化到 SDK 的事件对象/结构中（Event Normalization）。
- 提供发送消息 / 触发交互的封装（Send API 封装）。
- 支持流式更新（streaming）与卡片交互的抽象（接口/占位）。

## 依赖

建议安装官方常用的 PHP WebSocket 客户端：

```bash
composer require textalk/websocket
```

> 我们在 composer.json 中将 `textalk/websocket` 标记为依赖（如需替代实现，可注入自定义 WebSocket 客户端）。

## 快速开始示例

```php
use feishu\Core\Config;
use feishu\Core\TokenManager;
use feishu\Core\MemoryCache;
use feishu\Channel\Client as ChannelClient;

$config = new Config($appId, $appSecret);
$cache = new MemoryCache();
$tm = new TokenManager($config, null, $cache);

$channel = new ChannelClient($config, $tm);

// 事件回调
$channel->onEvent(function(array $event) {
    // 处理归一化后的事件
    var_dump($event);
});

// 连接并开始监听（阻塞）
$channel->connect();
```

## 注意

- 当前实现以最小侵入方式提供接口；在后续迭代中会补充完整的事件映射、策略（policy）与流式卡片支持。
- 在生产使用时请确保有自动重连、心跳、错误重试与日志上报机制。请参见本目录下的代码注释以扩展实现。
