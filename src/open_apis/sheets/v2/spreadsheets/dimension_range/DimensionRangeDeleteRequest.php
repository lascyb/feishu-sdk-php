<?php

namespace feishu\open_apis\sheets\v2\spreadsheets\dimension_range;

use feishu\Contract\RequestInterface;

/**
 * DimensionRangeDeleteRequest 删除电子表格行列
 * API：DELETE /open-apis/sheets/v2/spreadsheets/:spreadsheetToken/dimension_range
 */
class DimensionRangeDeleteRequest implements RequestInterface
{
    /** @var string 电子表格 token（路径参数） */
    private $spreadsheetToken;

    /** @var string 工作表 ID */
    private $sheetId;

    /** @var string 维度 {ROWS:行,COLUMNS:列} */
    private $majorDimension;

    /** @var int 起始行/列（从 1 开始，含） */
    private $startIndex;

    /** @var int 结束行/列（从 1 开始，含） */
    private $endIndex;

    /**
     * __construct 构造删除行列请求
     * @param string $spreadsheetToken 电子表格 token
     * @param string $sheetId 工作表 ID
     * @param string $majorDimension 维度 {ROWS:行,COLUMNS:列}
     * @param int $startIndex 起始位置
     * @param int $endIndex 结束位置
     */
    public function __construct($spreadsheetToken, $sheetId, $majorDimension, $startIndex, $endIndex)
    {
        $this->spreadsheetToken = (string)$spreadsheetToken;
        $this->sheetId = (string)$sheetId;
        $this->majorDimension = (string)$majorDimension;
        $this->startIndex = (int)$startIndex;
        $this->endIndex = (int)$endIndex;
    }

    /**
     * rows 删除行
     * @param string $spreadsheetToken 电子表格 token
     * @param string $sheetId 工作表 ID
     * @param int $startIndex 起始行
     * @param int $endIndex 结束行
     * @return self
     */
    public static function rows($spreadsheetToken, $sheetId, $startIndex, $endIndex)
    {
        return new self($spreadsheetToken, $sheetId, 'ROWS', $startIndex, $endIndex);
    }

    /**
     * columns 删除列
     * @param string $spreadsheetToken 电子表格 token
     * @param string $sheetId 工作表 ID
     * @param int $startIndex 起始列
     * @param int $endIndex 结束列
     * @return self
     */
    public static function columns($spreadsheetToken, $sheetId, $startIndex, $endIndex)
    {
        return new self($spreadsheetToken, $sheetId, 'COLUMNS', $startIndex, $endIndex);
    }

    /**
     * getMethod HTTP 方法
     * @return string
     */
    public function getMethod()
    {
        return 'DELETE';
    }

    /**
     * getPath API 路径
     * @return string
     */
    public function getPath()
    {
        return '/sheets/v2/spreadsheets/' . rawurlencode($this->spreadsheetToken) . '/dimension_range';
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
            'dimension' => [
                'sheetId' => $this->sheetId,
                'majorDimension' => $this->majorDimension,
                'startIndex' => $this->startIndex,
                'endIndex' => $this->endIndex,
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
