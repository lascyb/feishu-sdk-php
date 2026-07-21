# feishu/sdk-php

飞书（Lark）OpenAPI / 自定义机器人 Webhook PHP SDK。

仓库：[https://github.com/lascyb/feishu-sdk-php](https://github.com/lascyb/feishu-sdk-php)

## 安装

```bash
composer require feishu/sdk-php
```

## 快速使用

```php
use feishu\Client;
use feishu\open_apis\im\v1\messages\MessagesCreateRequest;

$client = new Client($appId, $appSecret);

$response = $client->request(new MessagesCreateRequest(/* ... */));
$data = $response->data();
```

任意接口（SDK 未封装时）：

```php
use feishu\AnyRequest;
use feishu\Client;

$client = new Client($appId, $appSecret);

// GET
$response = $client->request(AnyRequest::get('/contact/v3/users/' . $userId, [
    'user_id_type' => 'open_id',
]));

// POST
$response = $client->request(AnyRequest::post(
    '/im/v1/messages',
    [
        'receive_id' => $openId,
        'msg_type' => 'text',
        'content' => json_encode(['text' => 'hello'], JSON_UNESCAPED_UNICODE),
    ],
    ['receive_id_type' => 'open_id']
));

// 或直接构造：new AnyRequest('PATCH', '/path', $query, $body)
```

Webhook 机器人：

```php
use feishu\WebhookBot;

$bot = new WebhookBot($webhookUrl, $secret); // $secret：飞书后台开启签名校验时传入
$bot->requireSign();                          // 可选：强制要求签名密钥
$bot->setKeywords(['应用报警', '项目更新']);   // 可选：与后台「自定义关键词」一致时做发送前预校验

$bot->sendText('应用报警：服务异常');
$bot->sendText(WebhookBot::atUser('ou_xxx', 'Tom') . ' 请处理');
$bot->sendPost([
    'zh_cn' => [
        'title' => '项目更新',
        'content' => [[['tag' => 'text', 'text' => '项目有更新']]],
    ],
]);
$bot->sendImage('img_xxx');
$bot->sendShareChat('oc_xxx');
$bot->sendCard($card);
```

安全设置：

| 飞书后台 | SDK 支持 |
|----------|----------|
| 签名校验 | 构造传入 `$secret`，自动附加 `timestamp` / `sign`；可用 `requireSign()` 强制 |
| 自定义关键词 | `setKeywords()` 发送前预校验（只匹配 `text` / `title`） |
| IP 白名单 | 需在飞书后台配置，SDK 无法代校验 |

授权登录（OAuth2）：

```php
use feishu\OAuth2;

$oauth = new OAuth2($appId, $appSecret, $redirectUri);

// 跳转授权
$authUrl = $oauth->authorize(['auth:user.id:read'], $state);

// 回调换 token
$token = $oauth->getAccessToken($code);
// $token['access_token'] / $token['refresh_token'] ...

// 刷新 token
$token = $oauth->refreshAccessToken($refreshToken);
```

## 要求

- PHP >= 8.1
- ext-curl、ext-json

## License

MIT
