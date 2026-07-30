<?php

namespace feishu\open_apis\sheets\v2\spreadsheets\values_append;

use feishu\Contract\RequestInterface;

/**
 * ValuesAppendInsertRequest 追加数据
 * 文档：https://open.feishu.cn/document/server-docs/docs/sheets-v3/data-operation/append-data
 * API：POST /open-apis/sheets/v2/spreadsheets/:spreadsheet_token/values_append
 */
class ValuesAppendInsertRequest implements RequestInterface
{
    /** @var string 电子表格 token（路径参数） */
    private $spreadsheetToken;

    /** @var string 追加数据范围（sheetId!单元格范围，如 0!A1:C3） */
    private $range;

    /** @var array 追加数据（二维数组） */
    private $values;

    /** @var array|null 插入维度 {ROWS:按行插入,COLUMNS:按列插入} */
    private $majorDimension;

    /**
     * __construct 构造追加数据请求
     * @param string $spreadsheetToken 电子表格 token
     * @param string $range 追加范围（sheetId!单元格范围，如 0!A1:C3）
     * @param array $values 追加数据（二维数组）
     * @param string|null $majorDimension 插入维度 {ROWS,COLUMNS}
     */
    public function __construct($spreadsheetToken, $range, array $values, $majorDimension = null)
    {
        $this->spreadsheetToken = (string)$spreadsheetToken;
        $this->range = (string)$range;
        $this->values = $values;
        $this->majorDimension = $majorDimension;
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
     * getPath API 路径（含 spreadsheetToken）
     * @return string
     */
    public function getPath()
    {
        return '/sheets/v2/spreadsheets/' . rawurlencode($this->spreadsheetToken) . '/values_append';
    }

    /**
     * getQuery 查询参数
     * @return array
     */
    public function getQuery()
    {
        $query = [];

        if ($this->majorDimension !== null) {
            $query['major_dimension'] = $this->majorDimension;
        }

        return $query;
    }

    /**
     * getBody 请求体
     * @return array
     */
    public function getBody()
    {
        return [
            'range' => $this->range,
            'values' => $this->values,
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
