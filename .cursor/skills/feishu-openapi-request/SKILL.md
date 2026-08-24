---
name: feishu-openapi-request
description: >-
  根据飞书开放平台接口文档生成 feishu-sdk-php OpenAPI Request 类（实现 RequestInterface）。
  在用户提供飞书文档链接、要求生成请求类、或提到 open.feishu.cn/document、MessagesCreateRequest、open_apis 时使用。
---

# 飞书 OpenAPI Request 类生成

## 目标

根据飞书接口文档，在 `src/open_apis/` 下生成请求类，供 `feishu\Client::request()` 调用。

**不要**修改 `src/WebhookBot.php`（Webhook 与 OpenAPI 分离）。

## 前置：获取文档

1. 用户必须提供文档 URL（`https://open.feishu.cn/document/...`）或可读的文档正文。
2. **链接可访问时必须先抓取内容**；无法获取则提示用户并中止，勿臆造字段。
3. 从文档提取：**文档标题**、**HTTP Method**、**HTTP URL**、**查询参数**、**请求体**。

## 目录结构（以 HTTP URL 为准）

**目录仅依据 HTTP URL，不使用文档导航 URL。**

1. 去掉 `https://open.feishu.cn/open-apis/` 前缀。
2. 路径参数段（`:spreadsheetToken` 等）**不生成目录**，由构造函数传入并在 `getPath()` 拼接。
3. 其余静态段各对应一级目录（小写、下划线原样保留）。

示例：`.../sheets/v2/spreadsheets/:spreadsheetToken/values_prepend`

→ `src/open_apis/sheets/v2/spreadsheets/values_prepend/`

## 类名（HTTP 路径末段 + 文档标题动作）

**`{Resource}{Action}Request`**

| 来源 | 规则 | 示例 |
|------|------|------|
| Resource | HTTP URL 最后一段静态路径，snake_case → PascalCase | `messages` → `Messages`，`dimension_range` → `DimensionRange` |
| Action | 文档标题中的动词，转 PascalCase 英文 | 「发送消息」→ `Create`，「更新行列」→ `Update`，「插入数据」→ `Insert` |
| 结果 | 拼接 + `Request` | `MessagesCreateRequest`、`DimensionRangeUpdateRequest` |

同一路径多种操作（RESTful）时，靠 Action 区分，例如 `messages` 上 POST「发送消息」→ `MessagesCreateRequest`，GET「获取消息」→ `MessagesGetRequest`。

### 标题 → Action 常用映射

| 标题关键词 | Action |
|------------|--------|
| 发送、创建、新增 | Create |
| 更新、修改 | Update |
| 删除 | Delete |
| 获取、查询 | Get |
| 列表 | List |
| 搜索 | Search |
| 插入 | Insert |
| 追加 | Append |
| 批量创建 | BatchCreate |

HTTP Method 可作辅助（POST 多为 Create），**以文档标题为准**。

### 命名空间

与目录一致：`feishu\open_apis\sheets\v2\spreadsheets\values_prepend\ValuesPrependInsertRequest`

### getPath()

与 HTTP URL 一致，去掉 host 与 `/open-apis`；路径参数在 `getPath()` 内拼接。查询参数进 `getQuery()`。

## 类结构要求

1. `implements feishu\Contract\RequestInterface`
2. HTTP 方法只写在 `getMethod()`。
3. 类注释含文档标题、文档链接、`API：{METHOD} /open-apis{path}`。
4. 其余见 [template.md](template.md)。

## 参考实现

`src/open_apis/im/v1/messages/MessagesCreateRequest.php`

## 生成后检查

- [ ] 目录来自 HTTP URL 静态段
- [ ] 类名 = 路径末段 + 文档标题动作
- [ ] 文件路径、namespace、类名一致
- [ ] `getMethod()`、`getPath()` 与文档一致

## 调用示例

```php
use feishu\Client;
use feishu\open_apis\im\v1\messages\MessagesCreateRequest;

$client = new Client($appId, $appSecret);
$response = $client->request(MessagesCreateRequest::text('chat_id', 'oc_xxx', 'hello'));
```

更多示例见 [examples.md](examples.md).
