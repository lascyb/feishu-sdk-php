# 示例

## 示例 1：发送消息

**文档标题：** 发送消息  
**HTTP URL：** `POST https://open.feishu.cn/open-apis/im/v1/messages`

| 项 | 值 |
|----|-----|
| 目录 | `src/open_apis/im/v1/messages/` |
| 类名 | `Messages` + `Create` → `MessagesCreateRequest` |

参考：`src/open_apis/im/v1/messages/MessagesCreateRequest.php`

---

## 示例 2：插入数据

**文档标题：** 插入数据  
**HTTP URL：** `POST .../spreadsheets/:spreadsheetToken/values_prepend`

| 项 | 值 |
|----|-----|
| 目录 | `src/open_apis/sheets/v2/spreadsheets/values_prepend/` |
| 类名 | `ValuesPrepend` + `Insert` → `ValuesPrependInsertRequest` |

---

## 示例 3：更新行列

**文档标题：** 更新行列  
**HTTP URL：** `PUT .../spreadsheets/:spreadsheetToken/dimension_range`

| 项 | 值 |
|----|-----|
| 目录 | `src/open_apis/sheets/v2/spreadsheets/dimension_range/` |
| 类名 | `DimensionRange` + `Update` → `DimensionRangeUpdateRequest` |

---

## 类名生成

```
resource = PascalCase(HTTP路径最后静态段)   // messages → Messages
action   = 文档标题动词英文                  // 发送消息 → Create
class    = resource + action + "Request"    // MessagesCreateRequest
```
