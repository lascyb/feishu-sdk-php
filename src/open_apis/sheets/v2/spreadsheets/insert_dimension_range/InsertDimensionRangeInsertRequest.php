<?php

namespace feishu\open_apis\sheets\v2\spreadsheets\insert_dimension_range;

use feishu\Contract\RequestInterface;

/**
 * InsertDimensionRangeInsertRequest 插入行列
 * 文档：https://open.feishu.cn/document/server-docs/docs/sheets-v3/sheet-rowcol/insert-rows-or-columns
 * API：POST /open-apis/sheets/v2/spreadsheets/:spreadsheetToken/insert_dimension_range
 */
class InsertDimensionRangeInsertRequest implements RequestInterface
{
    /** @var string 电子表格 token（路径参数） */
    private $spreadsheetToken;

    /** @var string 工作表 ID */
    private $sheetId;

    /** @var string 维度 {ROWS:行,COLUMNS:列} */
    private $majorDimension;

    /** @var int 起始位置（从 0 开始，含） */
    private $startIndex;

    /** @var int 结束位置（从 0 开始，不含；插入数量 = endIndex - startIndex） */
    private $endIndex;

    /** @var string|null 继承样式 {BEFORE:起始位置,AFTER:结束位置} */
    private $inheritStyle;

    /**
     * __construct 构造插入行列请求
     * @param string $spreadsheetToken 电子表格 token
     * @param string $sheetId 工作表 ID
     * @param string $majorDimension 维度 {ROWS:行,COLUMNS:列}
     * @param int $startIndex 起始位置（从 0 开始）
     * @param int $endIndex 结束位置（从 0 开始，不含）
     * @param string|null $inheritStyle 继承样式 {BEFORE:起始位置,AFTER:结束位置}
     */
    public function __construct($spreadsheetToken, $sheetId, $majorDimension, $startIndex, $endIndex, $inheritStyle = null)
    {
        $this->spreadsheetToken = (string)$spreadsheetToken;
        $this->sheetId = (string)$sheetId;
        $this->majorDimension = (string)$majorDimension;
        $this->startIndex = (int)$startIndex;
        $this->endIndex = (int)$endIndex;
        $this->inheritStyle = $inheritStyle === null || $inheritStyle === '' ? null : (string)$inheritStyle;
    }

    /**
     * rows 插入行
     * @param string $spreadsheetToken 电子表格 token
     * @param string $sheetId 工作表 ID
     * @param int $startIndex 起始行（从 0 开始）
     * @param int $endIndex 结束行（从 0 开始，不含）
     * @param string|null $inheritStyle 继承样式
     * @return self
     */
    public static function rows($spreadsheetToken, $sheetId, $startIndex, $endIndex, $inheritStyle = null)
    {
        return new self($spreadsheetToken, $sheetId, 'ROWS', $startIndex, $endIndex, $inheritStyle);
    }

    /**
     * columns 插入列
     * @param string $spreadsheetToken 电子表格 token
     * @param string $sheetId 工作表 ID
     * @param int $startIndex 起始列（从 0 开始）
     * @param int $endIndex 结束列（从 0 开始，不含）
     * @param string|null $inheritStyle 继承样式
     * @return self
     */
    public static function columns($spreadsheetToken, $sheetId, $startIndex, $endIndex, $inheritStyle = null)
    {
        return new self($spreadsheetToken, $sheetId, 'COLUMNS', $startIndex, $endIndex, $inheritStyle);
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
        return '/sheets/v2/spreadsheets/' . rawurlencode($this->spreadsheetToken) . '/insert_dimension_range';
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
        $body = [
            'dimension' => [
                'sheetId' => $this->sheetId,
                'majorDimension' => $this->majorDimension,
                'startIndex' => $this->startIndex,
                'endIndex' => $this->endIndex,
            ],
        ];
        if ($this->inheritStyle !== null) {
            $body['inheritStyle'] = $this->inheritStyle;
        }

        return $body;
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
