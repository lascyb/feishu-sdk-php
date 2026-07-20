<?php

namespace feishu\open_apis\sheets\v2\spreadsheets\sheets_batch_update;

use feishu\Contract\RequestInterface;

/**
 * SheetsBatchUpdateUpdateRequest 操作工作表
 * 文档：https://open.feishu.cn/document/server-docs/docs/sheets-v3/spreadsheet-sheet/operate-sheets
 * API：POST /open-apis/sheets/v2/spreadsheets/:spreadsheet_token/sheets_batch_update
 */
class SheetsBatchUpdateUpdateRequest implements RequestInterface
{
    /** @var string 电子表格 token（路径参数） */
    private $spreadsheetToken;

    /** @var array 工作表操作列表（addSheet/copySheet/deleteSheet） */
    private $requests;

    /**
     * __construct 构造操作工作表请求
     * @param string $spreadsheetToken 电子表格 token
     * @param array $requests 操作列表
     */
    public function __construct($spreadsheetToken, array $requests)
    {
        $this->spreadsheetToken = (string)$spreadsheetToken;
        $this->requests = $requests;
    }

    /**
     * addSheet 新增工作表
     * @param string $spreadsheetToken 电子表格 token
     * @param string $title 工作表标题
     * @param int|null $index 插入位置（默认 0）
     * @return self
     */
    public static function addSheet($spreadsheetToken, $title, $index = null)
    {
        $properties = ['title' => (string)$title];
        if ($index !== null) {
            $properties['index'] = (int)$index;
        }

        return new self($spreadsheetToken, [
            ['addSheet' => ['properties' => $properties]],
        ]);
    }

    /**
     * copySheet 复制工作表
     * @param string $spreadsheetToken 电子表格 token
     * @param string $sourceSheetId 源工作表 ID
     * @param string|null $title 新工作表标题（空则使用默认命名）
     * @return self
     */
    public static function copySheet($spreadsheetToken, $sourceSheetId, $title = null)
    {
        $destination = [];
        if ($title !== null && $title !== '') {
            $destination['title'] = (string)$title;
        }

        return new self($spreadsheetToken, [
            [
                'copySheet' => [
                    'source' => ['sheetId' => (string)$sourceSheetId],
                    'destination' => $destination,
                ],
            ],
        ]);
    }

    /**
     * deleteSheet 删除工作表
     * @param string $spreadsheetToken 电子表格 token
     * @param string $sheetId 工作表 ID
     * @return self
     */
    public static function deleteSheet($spreadsheetToken, $sheetId)
    {
        return new self($spreadsheetToken, [
            ['deleteSheet' => ['sheetId' => (string)$sheetId]],
        ]);
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
        return [];
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
