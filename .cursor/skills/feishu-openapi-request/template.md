# Request 类模板

## 文件

`src/open_apis/{http_path_segments}/{Resource}{Action}Request.php`

- `{http_path_segments}`：HTTP URL 静态段（跳过 `:param`）
- `{Resource}`：末段 snake_case → PascalCase
- `{Action}`：文档标题动作 → PascalCase（如 Create、Update）

## 代码模板

```php
<?php

namespace feishu\open_apis\{path_namespace};

use feishu\Contract\RequestInterface;

/**
 * {Resource}{Action}Request {文档标题}
 * 文档：{doc_url}
 * API：{METHOD} /open-apis{api_path}
 */
class {Resource}{Action}Request implements RequestInterface
{
    // 字段注释与字段同行

    /**
     * __construct 构造请求
     */
    public function __construct(/* 必填参数 */)
    {
    }

    public function getMethod() { return '{METHOD}'; }
    public function getPath() { return '{api_path}'; }
    public function getQuery() { return []; }
    public function getBody() { return []; }
    public function getHeaders() { return []; }
}
```

## 示例

| 文档标题 | HTTP 末段 | 类名 |
|----------|-----------|------|
| 发送消息 | messages | MessagesCreateRequest |
| 更新行列 | dimension_range | DimensionRangeUpdateRequest |
| 插入数据 | values_prepend | ValuesPrependInsertRequest |
