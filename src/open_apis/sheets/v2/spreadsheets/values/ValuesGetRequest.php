<?php

namespace feishu\open_apis\sheets\v2\spreadsheets\values;

use feishu\Contract\RequestInterface;

/**
 * ValuesGetRequest 读取单个范围
 * 文档：https://open.feishu.cn/document/server-docs/docs/sheets-v3/data-operation/reading-a-single-range
 * API：GET /open-apis/sheets/v2/spreadsheets/:spreadsheetToken/values/:range
 */
class ValuesGetRequest implements RequestInterface
{
    /** @var string 电子表格 token（路径参数） */
    private $spreadsheetToken;

    /** @var string 查询范围，格式 sheetId!开始:结束，如 Q7PlXT!A1:B2 */
    private $range;

    /** @var string|null 单元格数据格式 {ToString:纯文本,Formula:公式,FormattedValue:计算并格式化,UnformattedValue:计算不格式化} */
    private $valueRenderOption;

    /** @var string|null 日期时间格式 {FormattedString:格式化字符串} */
    private $dateTimeRenderOption;

    /** @var string|null 用户 ID 类型 {open_id:应用内用户ID,union_id:开发商下用户ID} */
    private $userIdType;

    /**
     * __construct 构造读取单个范围请求
     * @param string $spreadsheetToken 电子表格 token
     * @param string $range 查询范围（sheetId!单元格范围）
     * @param string|null $valueRenderOption 单元格数据格式
     * @param string|null $dateTimeRenderOption 日期时间格式
     * @param string|null $userIdType 用户 ID 类型
     */
    public function __construct(
        $spreadsheetToken,
        $range,
        $valueRenderOption = null,
        $dateTimeRenderOption = null,
        $userIdType = null
    ) {
        $this->spreadsheetToken = (string)$spreadsheetToken;
        $this->range = (string)$range;
        $this->valueRenderOption = $valueRenderOption === null || $valueRenderOption === ''
            ? null
            : (string)$valueRenderOption;
        $this->dateTimeRenderOption = $dateTimeRenderOption === null || $dateTimeRenderOption === ''
            ? null
            : (string)$dateTimeRenderOption;
        $this->userIdType = $userIdType === null || $userIdType === ''
            ? null
            : (string)$userIdType;
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
        return '/sheets/v2/spreadsheets/' . rawurlencode($this->spreadsheetToken)
            . '/values/' . rawurlencode($this->range);
    }

    /**
     * getQuery 查询参数
     * @return array
     */
    public function getQuery()
    {
        $query = [];

        if ($this->valueRenderOption !== null) {
            $query['valueRenderOption'] = $this->valueRenderOption;
        }
        if ($this->dateTimeRenderOption !== null) {
            $query['dateTimeRenderOption'] = $this->dateTimeRenderOption;
        }
        if ($this->userIdType !== null) {
            $query['user_id_type'] = $this->userIdType;
        }

        return $query;
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
