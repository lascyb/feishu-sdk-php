<?php

namespace feishu\open_apis\sheets\v2\spreadsheets\styles_batch_update;

use feishu\Contract\RequestInterface;

/**
 * StylesBatchUpdateBatchSetRequest 批量设置单元格样式
 * 文档：https://open.feishu.cn/document/server-docs/docs/sheets-v3/data-operation/batch-set-cell-style
 * API：PUT /open-apis/sheets/v2/spreadsheets/:spreadsheetToken/styles_batch_update
 */
class StylesBatchUpdateBatchSetRequest implements RequestInterface
{
    /** @var string 电子表格 token（路径参数） */
    private $spreadsheetToken;

    /** @var array 样式与范围列表，每项含 ranges、style */
    private $data;

    /**
     * __construct 构造批量设置样式请求
     * @param string $spreadsheetToken 电子表格 token
     * @param array $data 样式配置列表，格式 [['ranges'=>[], 'style'=>[]], ...]
     */
    public function __construct($spreadsheetToken, array $data)
    {
        $this->spreadsheetToken = (string)$spreadsheetToken;
        $this->data = $data;
    }

    /**
     * withStyle 单组范围与样式
     * @param string $spreadsheetToken 电子表格 token
     * @param array $ranges 范围列表
     * @param array $style 样式
     * @return self
     */
    public static function withStyle($spreadsheetToken, array $ranges, array $style)
    {
        return new self($spreadsheetToken, [
            ['ranges' => $ranges, 'style' => $style],
        ]);
    }

    /**
     * getMethod HTTP 方法
     * @return string
     */
    public function getMethod()
    {
        return 'PUT';
    }

    /**
     * getPath API 路径
     * @return string
     */
    public function getPath()
    {
        return '/sheets/v2/spreadsheets/' . rawurlencode($this->spreadsheetToken) . '/styles_batch_update';
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
        return ['data' => $this->data];
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
