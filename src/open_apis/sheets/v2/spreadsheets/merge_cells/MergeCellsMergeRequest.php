<?php

namespace feishu\open_apis\sheets\v2\spreadsheets\merge_cells;

use feishu\Contract\RequestInterface;

/**
 * MergeCellsMergeRequest 合并单元格
 * 文档：https://open.feishu.cn/document/server-docs/docs/sheets-v3/data-operation/merge-cells
 * API：POST /open-apis/sheets/v2/spreadsheets/:spreadsheetToken/merge_cells
 */
class MergeCellsMergeRequest implements RequestInterface
{
    /** @var string 电子表格 token（路径参数） */
    private $spreadsheetToken;

    /** @var string 合并范围，如 sheetId!A2:B5 */
    private $range;

    /** @var string 合并类型 {MERGE_ALL:合并所有单元格,MERGE_ROWS:按行合并,MERGE_COLUMNS:按列合并} */
    private $mergeType;

    /**
     * __construct 构造合并单元格请求
     * @param string $spreadsheetToken 电子表格 token
     * @param string $range 合并范围
     * @param string $mergeType 合并类型
     */
    public function __construct($spreadsheetToken, $range, $mergeType = 'MERGE_COLUMNS')
    {
        $this->spreadsheetToken = (string)$spreadsheetToken;
        $this->range = (string)$range;
        $this->mergeType = (string)$mergeType;
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
        return '/sheets/v2/spreadsheets/' . rawurlencode($this->spreadsheetToken) . '/merge_cells';
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
            'range' => $this->range,
            'mergeType' => $this->mergeType,
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
