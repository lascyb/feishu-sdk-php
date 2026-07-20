<?php

namespace feishu\open_apis\sheets\v2\spreadsheets\sheets_batch_update;

use feishu\Contract\RequestInterface;

/**
 * SheetsBatchUpdateUpdatePropertiesRequest 更新工作表属性
 * 文档：https://open.feishu.cn/document/server-docs/docs/sheets-v3/spreadsheet-sheet/update-sheet-properties
 * API：POST /open-apis/sheets/v2/spreadsheets/:spreadsheet_token/sheets_batch_update
 */
class SheetsBatchUpdateUpdatePropertiesRequest implements RequestInterface
{
    /** @var string 电子表格 token（路径参数） */
    private $spreadsheetToken;

    /** @var array 更新工作表属性操作列表 */
    private $requests;

    /** @var string|null 用户 ID 类型 {open_id:应用内用户ID,union_id:开发商下用户ID} */
    private $userIdType;

    /**
     * __construct 构造更新工作表属性请求
     * @param string $spreadsheetToken 电子表格 token
     * @param array $requests 操作列表
     * @param string|null $userIdType 用户 ID 类型
     */
    public function __construct($spreadsheetToken, array $requests, $userIdType = null)
    {
        $this->spreadsheetToken = (string)$spreadsheetToken;
        $this->requests = $requests;
        $this->userIdType = $userIdType === null || $userIdType === ''
            ? null
            : (string)$userIdType;
    }

    /**
     * updateSheet 更新单个工作表属性
     * @param string $spreadsheetToken 电子表格 token
     * @param string $sheetId 工作表 ID
     * @param array $properties 可选属性（title/index/hidden/frozenRowCount/frozenColCount/protect）
     * @param string|null $userIdType 用户 ID 类型
     * @return self
     */
    public static function updateSheet($spreadsheetToken, $sheetId, array $properties = [], $userIdType = null)
    {
        return new self($spreadsheetToken, [
            [
                'updateSheet' => [
                    'properties' => array_merge(
                        ['sheetId' => (string)$sheetId],
                        $properties
                    ),
                ],
            ],
        ], $userIdType);
    }

    /**
     * protect 保护工作表
     * @param string $spreadsheetToken 电子表格 token
     * @param string $sheetId 工作表 ID
     * @param string|null $lockInfo 保护备注
     * @param array $userIDs 额外拥有编辑权限的用户 ID 列表
     * @param string|null $userIdType 用户 ID 类型
     * @return self
     */
    public static function protect(
        $spreadsheetToken,
        $sheetId,
        $lockInfo = null,
        array $userIDs = [],
        $userIdType = null
    ) {
        $protect = ['lock' => 'LOCK'];
        if ($lockInfo !== null && $lockInfo !== '') {
            $protect['lockInfo'] = (string)$lockInfo;
        }
        if (!empty($userIDs)) {
            $protect['userIDs'] = array_values($userIDs);
        }

        return self::updateSheet($spreadsheetToken, $sheetId, ['protect' => $protect], $userIdType);
    }

    /**
     * unlock 取消保护工作表
     * @param string $spreadsheetToken 电子表格 token
     * @param string $sheetId 工作表 ID
     * @param string|null $userIdType 用户 ID 类型
     * @return self
     */
    public static function unlock($spreadsheetToken, $sheetId, $userIdType = null)
    {
        return self::updateSheet($spreadsheetToken, $sheetId, [
            'protect' => ['lock' => 'UNLOCK'],
        ], $userIdType);
    }

    /**
     * getMethod HTTP 方法
     * @return string
     */
    public function getMethod()
    {
        return 'POST';
    }

    /**
     * getPath API 路径
     * @return string
     */
    public function getPath()
    {
        return '/sheets/v2/spreadsheets/' . rawurlencode($this->spreadsheetToken) . '/sheets_batch_update';
    }

    /**
     * getQuery 查询参数
     * @return array
     */
    public function getQuery()
    {
        if ($this->userIdType === null) {
            return [];
        }

        return [
            'user_id_type' => $this->userIdType,
        ];
    }

    /**
     * getBody 请求体
     * @return array
     */
    public function getBody()
    {
        return [
            'requests' => $this->requests,
        ];
    }

    /**
     * getHeaders 额外请求头
     * @return array
     */
    public function getHeaders()
    {
        return [];
    }
}
