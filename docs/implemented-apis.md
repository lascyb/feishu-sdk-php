# 已实现的 OpenAPI 接口

本文档汇总 `src/open_apis` 下已封装的 Request 类，便于查阅与选用。

**共计 22 个接口**

## 使用方式

```php
use feishu\Client;
use feishu\open_apis\im\v1\messages\MessagesCreateRequest;

$client = new Client($appId, $appSecret);
$response = $client->request(new MessagesCreateRequest(/* ... */));
```

未列出的接口可使用 `AnyRequest` 直接调用，参见 [README](../README.md)。

## 目录

- [身份验证 (authen)](#authen)
- [通讯录 (contact)](#contact)
- [云文档 (drive)](#drive)
- [即时消息 (im)](#im)
- [审批 (approval)](#approval)
- [电子表格 (sheets)](#sheets)

<a id="authen"></a>

## 身份验证 (authen)

| 类名 | 说明 | 方法 | 路径 | 文档 |
|------|------|------|------|------|
| `UserInfoGetRequest` | 获取用户信息（user_access_token） | GET | `/authen/v1/user_info` | [链接](https://open.feishu.cn/document/server-docs/authentication-management/login-state-management/get) |

命名空间前缀：`feishu\open_apis\authen`

<details>
<summary>完整类名与源文件</summary>

| 完整类名 | 源文件 |
|----------|--------|
| `feishu\open_apis\authen\v1\user_info\UserInfoGetRequest` | `authen/v1/user_info/UserInfoGetRequest.php` |

</details>

<a id="contact"></a>

## 通讯录 (contact)

| 类名 | 说明 | 方法 | 路径 | 文档 |
|------|------|------|------|------|
| `ChildrenListRequest` | 获取子部门列表 | GET | `/contact/v3/departments/:department_id/children` | [链接](https://open.feishu.cn/document/server-docs/contact-v3/department/children) |
| `FindByDepartmentGetRequest` | 获取部门直属用户列表 | GET | `/contact/v3/users/find_by_department` | [链接](https://open.feishu.cn/document/server-docs/contact-v3/user/find_by_department) |

命名空间前缀：`feishu\open_apis\contact`

<details>
<summary>完整类名与源文件</summary>

| 完整类名 | 源文件 |
|----------|--------|
| `feishu\open_apis\contact\v3\departments\children\ChildrenListRequest` | `contact/v3/departments/children/ChildrenListRequest.php` |
| `feishu\open_apis\contact\v3\users\find_by_department\FindByDepartmentGetRequest` | `contact/v3/users/find_by_department/FindByDepartmentGetRequest.php` |

</details>

<a id="drive"></a>

## 云文档 (drive)

| 类名 | 说明 | 方法 | 路径 | 文档 |
|------|------|------|------|------|
| `FilesCopyRequest` | 复制文件 | POST | `/drive/v1/files/:file_token/copy` | [链接](https://open.feishu.cn/document/server-docs/docs/drive-v1/file/copy) |

命名空间前缀：`feishu\open_apis\drive`

<details>
<summary>完整类名与源文件</summary>

| 完整类名 | 源文件 |
|----------|--------|
| `feishu\open_apis\drive\v1\files\copy\FilesCopyRequest` | `drive/v1/files/copy/FilesCopyRequest.php` |

</details>

<a id="im"></a>

## 即时消息 (im)

| 类名 | 说明 | 方法 | 路径 | 文档 |
|------|------|------|------|------|
| `MessagesCreateRequest` | 发送消息 | POST | `/im/v1/messages` | [链接](https://open.feishu.cn/document/server-docs/im-v1/message/create) |

命名空间前缀：`feishu\open_apis\im`

<details>
<summary>完整类名与源文件</summary>

| 完整类名 | 源文件 |
|----------|--------|
| `feishu\open_apis\im\v1\messages\MessagesCreateRequest` | `im/v1/messages/MessagesCreateRequest.php` |

</details>

<a id="approval"></a>

## 审批 (approval)

| 类名 | 说明 | 方法 | 路径 | 文档 |
|------|------|------|------|------|
| `SendCreateRequest` | 发送审批 Bot 消息 | POST | `/approval/v1/message/send` | [链接](https://open.feishu.cn/document/server-docs/approval-v4/message/send-bot-messages) |
| `UpdateUpdateRequest` | 更新审批 Bot 消息 | POST | `/approval/v1/message/update` | [链接](https://open.feishu.cn/document/server-docs/approval-v4/message/update-bot-messages) |
| `ExternalApprovalsCreateRequest` | 创建三方审批定义 | POST | `/approval/v4/external_approvals` | [链接](https://open.feishu.cn/document/server-docs/approval-v4/external_approval/create) |
| `ExternalInstancesCreateRequest` | 同步三方审批实例 | POST | `/approval/v4/external_instances` | [链接](https://open.feishu.cn/document/server-docs/approval-v4/external_instance/create) |

命名空间前缀：`feishu\open_apis\approval`

<details>
<summary>完整类名与源文件</summary>

| 完整类名 | 源文件 |
|----------|--------|
| `feishu\open_apis\approval\v1\message\send\SendCreateRequest` | `approval/v1/message/send/SendCreateRequest.php` |
| `feishu\open_apis\approval\v1\message\update\UpdateUpdateRequest` | `approval/v1/message/update/UpdateUpdateRequest.php` |
| `feishu\open_apis\approval\v4\external_approvals\ExternalApprovalsCreateRequest` | `approval/v4/external_approvals/ExternalApprovalsCreateRequest.php` |
| `feishu\open_apis\approval\v4\external_instances\ExternalInstancesCreateRequest` | `approval/v4/external_instances/ExternalInstancesCreateRequest.php` |

</details>

<a id="sheets"></a>

## 电子表格 (sheets)

| 类名 | 说明 | 方法 | 路径 | 文档 |
|------|------|------|------|------|
| `DimensionRangeDeleteRequest` | 删除电子表格行列 | DELETE | `/sheets/v2/spreadsheets/:spreadsheetToken/dimension_range` | - |
| `DimensionRangeUpdateRequest` | 更新电子表格行列属性（隐藏/显示、行高列宽） | PUT | `/sheets/v2/spreadsheets/:spreadsheetToken/dimension_range` | [链接](https://open.feishu.cn/document/server-docs/docs/sheets-v3/sheet-rowcol/update-rows-or-columns) |
| `InsertDimensionRangeInsertRequest` | 插入行列 | POST | `/sheets/v2/spreadsheets/:spreadsheetToken/insert_dimension_range` | [链接](https://open.feishu.cn/document/server-docs/docs/sheets-v3/sheet-rowcol/insert-rows-or-columns) |
| `MergeCellsMergeRequest` | 合并单元格 | POST | `/sheets/v2/spreadsheets/:spreadsheetToken/merge_cells` | [链接](https://open.feishu.cn/document/server-docs/docs/sheets-v3/data-operation/merge-cells) |
| `StylesBatchUpdateBatchSetRequest` | 批量设置单元格样式 | PUT | `/sheets/v2/spreadsheets/:spreadsheetToken/styles_batch_update` | [链接](https://open.feishu.cn/document/server-docs/docs/sheets-v3/data-operation/batch-set-cell-style) |
| `ValuesWriteRequest` | 向单个范围写入数据 | PUT | `/sheets/v2/spreadsheets/:spreadsheetToken/values` | [链接](https://open.feishu.cn/document/server-docs/docs/sheets-v3/data-operation/write-data-to-a-single-range) |
| `ValuesGetRequest` | 读取单个范围 | GET | `/sheets/v2/spreadsheets/:spreadsheetToken/values/:range` | [链接](https://open.feishu.cn/document/server-docs/docs/sheets-v3/data-operation/reading-a-single-range) |
| `ValuesBatchGetGetRequest` | 读取多个范围 | GET | `/sheets/v2/spreadsheets/:spreadsheetToken/values_batch_get` | [链接](https://open.feishu.cn/document/server-docs/docs/sheets-v3/data-operation/reading-multiple-ranges) |
| `ValuesPrependInsertRequest` | 在指定范围起始位置上方插入行并填充数据 | POST | `/sheets/v2/spreadsheets/:spreadsheetToken/values_prepend` | [链接](https://open.feishu.cn/document/server-docs/docs/sheets-v3/data-operation/prepend-data) |
| `SheetsBatchUpdateUpdatePropertiesRequest` | 更新工作表属性 | POST | `/sheets/v2/spreadsheets/:spreadsheet_token/sheets_batch_update` | [链接](https://open.feishu.cn/document/server-docs/docs/sheets-v3/spreadsheet-sheet/update-sheet-properties) |
| `SheetsBatchUpdateUpdateRequest` | 操作工作表 | POST | `/sheets/v2/spreadsheets/:spreadsheet_token/sheets_batch_update` | [链接](https://open.feishu.cn/document/server-docs/docs/sheets-v3/spreadsheet-sheet/operate-sheets) |
| `ValuesAppendInsertRequest` | 追加数据 | POST | `/sheets/v2/spreadsheets/:spreadsheet_token/values_append` | [链接](https://open.feishu.cn/document/server-docs/docs/sheets-v3/data-operation/append-data) |
| `SheetsGetRequest` | 查询工作表 | GET | `/sheets/v3/spreadsheets/:spreadsheet_token/sheets/:sheet_id` | [链接](https://open.feishu.cn/document/server-docs/docs/sheets-v3/spreadsheet-sheet/get) |

命名空间前缀：`feishu\open_apis\sheets`

<details>
<summary>完整类名与源文件</summary>

| 完整类名 | 源文件 |
|----------|--------|
| `feishu\open_apis\sheets\v2\spreadsheets\dimension_range\DimensionRangeDeleteRequest` | `sheets/v2/spreadsheets/dimension_range/DimensionRangeDeleteRequest.php` |
| `feishu\open_apis\sheets\v2\spreadsheets\dimension_range\DimensionRangeUpdateRequest` | `sheets/v2/spreadsheets/dimension_range/DimensionRangeUpdateRequest.php` |
| `feishu\open_apis\sheets\v2\spreadsheets\insert_dimension_range\InsertDimensionRangeInsertRequest` | `sheets/v2/spreadsheets/insert_dimension_range/InsertDimensionRangeInsertRequest.php` |
| `feishu\open_apis\sheets\v2\spreadsheets\merge_cells\MergeCellsMergeRequest` | `sheets/v2/spreadsheets/merge_cells/MergeCellsMergeRequest.php` |
| `feishu\open_apis\sheets\v2\spreadsheets\styles_batch_update\StylesBatchUpdateBatchSetRequest` | `sheets/v2/spreadsheets/styles_batch_update/StylesBatchUpdateBatchSetRequest.php` |
| `feishu\open_apis\sheets\v2\spreadsheets\values\ValuesWriteRequest` | `sheets/v2/spreadsheets/values/ValuesWriteRequest.php` |
| `feishu\open_apis\sheets\v2\spreadsheets\values\ValuesGetRequest` | `sheets/v2/spreadsheets/values/ValuesGetRequest.php` |
| `feishu\open_apis\sheets\v2\spreadsheets\values_batch_get\ValuesBatchGetGetRequest` | `sheets/v2/spreadsheets/values_batch_get/ValuesBatchGetGetRequest.php` |
| `feishu\open_apis\sheets\v2\spreadsheets\values_prepend\ValuesPrependInsertRequest` | `sheets/v2/spreadsheets/values_prepend/ValuesPrependInsertRequest.php` |
| `feishu\open_apis\sheets\v2\spreadsheets\sheets_batch_update\SheetsBatchUpdateUpdatePropertiesRequest` | `sheets/v2/spreadsheets/sheets_batch_update/SheetsBatchUpdateUpdatePropertiesRequest.php` |
| `feishu\open_apis\sheets\v2\spreadsheets\sheets_batch_update\SheetsBatchUpdateUpdateRequest` | `sheets/v2/spreadsheets/sheets_batch_update/SheetsBatchUpdateUpdateRequest.php` |
| `feishu\open_apis\sheets\v2\spreadsheets\values_append\ValuesAppendInsertRequest` | `sheets/v2/spreadsheets/values_append/ValuesAppendInsertRequest.php` |
| `feishu\open_apis\sheets\v3\spreadsheets\sheets\SheetsGetRequest` | `sheets/v3/spreadsheets/sheets/SheetsGetRequest.php` |

</details>
