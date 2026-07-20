<?php

namespace feishu\open_apis\sheets\v3\spreadsheets\sheets;

use feishu\Contract\RequestInterface;

/**
 * SheetsGetRequest 查询工作表
 * 文档：https://open.feishu.cn/document/server-docs/docs/sheets-v3/spreadsheet-sheet/get
 * API：GET /open-apis/sheets/v3/spreadsheets/:spreadsheet_token/sheets/:sheet_id
 */
class SheetsGetRequest implements RequestInterface
{
    /** @var string 电子表格 token（路径参数） */
    private $spreadsheetToken;

    /** @var string 工作表 ID（路径参数） */
    private $sheetId;

    /**
     * __construct 构造查询工作表请求
     * @param string $spreadsheetToken 电子表格 token
     * @param string $sheetId 工作表 ID
     */
    public function __construct($spreadsheetToken, $sheetId)
    {
        $this->spreadsheetToken = (string)$spreadsheetToken;
        $this->sheetId = (string)$sheetId;
    }

    /**
     * getMethod HTTP 方法
     * @return string
     */
    public function getMethod()
    {
        return 'GET';
    }

    /**
     * getPath API 路径
     * @return string
     */
    public function getPath()
    {
        return '/sheets/v3/spreadsheets/' . rawurlencode($this->spreadsheetToken)
            . '/sheets/' . rawurlencode($this->sheetId);
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
     * @return null
     */
    public function getBody()
    {
        return null;
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
