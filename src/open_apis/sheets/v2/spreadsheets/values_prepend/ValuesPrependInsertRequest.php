<?php

namespace feishu\open_apis\sheets\v2\spreadsheets\values_prepend;

use feishu\Contract\RequestInterface;

/**
 * ValuesPrependInsertRequest 在指定范围起始位置上方插入行并填充数据
 * 文档：https://open.feishu.cn/document/server-docs/docs/sheets-v3/data-operation/prepend-data
 * API：POST /open-apis/sheets/v2/spreadsheets/:spreadsheetToken/values_prepend
 */
class ValuesPrependInsertRequest implements RequestInterface
{
    /** @var string 电子表格 token（路径参数） */
    private $spreadsheetToken;

    /** @var string 写入范围，格式 sheetId!开始:结束，如 8fe9d6!A2:B5 */
    private $range;

    /** @var array<array> 插入的二维单元格数据 */
    private $values;

    /**
     * __construct 构造插入数据请求
     * @param string $spreadsheetToken 电子表格 token
     * @param string $range 写入范围（sheetId!单元格范围）
     * @param array $values 插入数据（二维数组）
     */
    public function __construct($spreadsheetToken, $range, array $values)
    {
        $this->spreadsheetToken = (string)$spreadsheetToken;
        $this->range = (string)$range;
        $this->values = $values;
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
        return '/sheets/v2/spreadsheets/' . rawurlencode($this->spreadsheetToken) . '/values_prepend';
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
            'valueRange' => [
                'range' => $this->range,
                'values' => $this->values,
            ],
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
