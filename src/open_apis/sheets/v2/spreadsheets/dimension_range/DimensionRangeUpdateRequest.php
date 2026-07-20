<?php

namespace feishu\open_apis\sheets\v2\spreadsheets\dimension_range;

use feishu\Contract\RequestInterface;

/**
 * DimensionRangeUpdateRequest 更新电子表格行列属性（隐藏/显示、行高列宽）
 * 文档：https://open.feishu.cn/document/server-docs/docs/sheets-v3/sheet-rowcol/update-rows-or-columns
 * API：PUT /open-apis/sheets/v2/spreadsheets/:spreadsheetToken/dimension_range
 */
class DimensionRangeUpdateRequest implements RequestInterface
{
    /** @var string 电子表格 token（路径参数） */
    private $spreadsheetToken;

    /** @var string 工作表 ID */
    private $sheetId;

    /** @var string 更新维度 {ROWS:行,COLUMNS:列} */
    private $majorDimension;

    /** @var int 起始行/列（从 1 开始，含） */
    private $startIndex;

    /** @var int 结束行/列（从 1 开始，含） */
    private $endIndex;

    /** @var bool|null 是否显示行或列 */
    private $visible;

    /** @var int|null 行高或列宽（像素，0 等价于隐藏） */
    private $fixedSize;

    /**
     * __construct 构造更新行列请求
     * @param string $spreadsheetToken 电子表格 token
     * @param string $sheetId 工作表 ID
     * @param string $majorDimension 维度 {ROWS:行,COLUMNS:列}
     * @param int $startIndex 起始位置（从 1 开始）
     * @param int $endIndex 结束位置（从 1 开始）
     * @param bool|null $visible 是否显示（dimensionProperties 至少填一项）
     * @param int|null $fixedSize 行高或列宽像素值
     */
    public function __construct(
        $spreadsheetToken,
        $sheetId,
        $majorDimension,
        $startIndex,
        $endIndex,
        $visible = null,
        $fixedSize = null
    ) {
        $this->spreadsheetToken = (string)$spreadsheetToken;
        $this->sheetId = (string)$sheetId;
        $this->majorDimension = (string)$majorDimension;
        $this->startIndex = (int)$startIndex;
        $this->endIndex = (int)$endIndex;
        $this->visible = $visible === null ? null : (bool)$visible;
        $this->fixedSize = $fixedSize === null ? null : (int)$fixedSize;
    }

    /**
     * rows 更新行属性
     * @param string $spreadsheetToken 电子表格 token
     * @param string $sheetId 工作表 ID
     * @param int $startIndex 起始行
     * @param int $endIndex 结束行
     * @param bool|null $visible 是否显示
     * @param int|null $fixedSize 行高像素
     * @return self
     */
    public static function rows(
        $spreadsheetToken,
        $sheetId,
        $startIndex,
        $endIndex,
        $visible = null,
        $fixedSize = null
    ) {
        return new self($spreadsheetToken, $sheetId, 'ROWS', $startIndex, $endIndex, $visible, $fixedSize);
    }

    /**
     * columns 更新列属性
     * @param string $spreadsheetToken 电子表格 token
     * @param string $sheetId 工作表 ID
     * @param int $startIndex 起始列
     * @param int $endIndex 结束列
     * @param bool|null $visible 是否显示
     * @param int|null $fixedSize 列宽像素
     * @return self
     */
    public static function columns(
        $spreadsheetToken,
        $sheetId,
        $startIndex,
        $endIndex,
        $visible = null,
        $fixedSize = null
    ) {
        return new self($spreadsheetToken, $sheetId, 'COLUMNS', $startIndex, $endIndex, $visible, $fixedSize);
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
     * getPath API 路径（含 spreadsheetToken）
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
        $dimensionProperties = [];
        if ($this->visible !== null) {
            $dimensionProperties['visible'] = $this->visible;
        }
        if ($this->fixedSize !== null) {
            $dimensionProperties['fixedSize'] = $this->fixedSize;
        }

        return [
            'dimension' => [
                'sheetId' => $this->sheetId,
                'majorDimension' => $this->majorDimension,
                'startIndex' => $this->startIndex,
                'endIndex' => $this->endIndex,
            ],
            'dimensionProperties' => $dimensionProperties,
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
