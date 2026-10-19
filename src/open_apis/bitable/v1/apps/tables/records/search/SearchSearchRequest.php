<?php

namespace feishu\open_apis\bitable\v1\apps\tables\records\search;

use feishu\Contract\RequestInterface;

/**
 * SearchSearchRequest 查询记录
 * 文档：https://open.feishu.cn/document/server-docs/docs/bitable-v1/app-table-record/search
 * API：POST /open-apis/bitable/v1/apps/:app_token/tables/:table_id/records/search
 */
class SearchSearchRequest implements RequestInterface
{
    /** @var string 多维表格 app_token */
    private $appToken;

    /** @var string 数据表 table_id */
    private $tableId;

    /** @var array|null 筛选条件，含 conjunction 与 conditions 或 children */
    private $filter;

    /** @var list<string>|null 指定返回字段名 */
    private $fieldNames;

    /** @var list<array{field_name: string, desc?: bool}>|null 排序 */
    private $sort;

    /** @var string|null 视图 ID */
    private $viewId;

    /** @var bool|null 是否返回创建时间、修改时间、创建人、修改人 */
    private $automaticFields;

    /** @var string|null 分页标记 */
    private $pageToken;

    /** @var int|null 分页大小，最大 500 */
    private $pageSize;

    /** @var string|null 用户 ID 类型 {open_id:Open ID,union_id:Union ID,user_id:User ID} */
    private $userIdType;

    /**
     * __construct 构造查询记录请求
     * @param string $appToken 多维表格 app_token
     * @param string $tableId 数据表 table_id
     * @param array|null $filter 筛选条件
     * @param int|null $pageSize 分页大小
     * @param string|null $pageToken 分页标记
     */
    public function __construct($appToken, $tableId, ?array $filter = null, $pageSize = null, $pageToken = null)
    {
        $appToken = trim((string)$appToken);
        $tableId = trim((string)$tableId);
        if ($appToken === '' || $tableId === '') {
            throw new \InvalidArgumentException('app_token 与 table_id 不能为空');
        }
        if ($pageSize !== null) {
            $pageSize = (int)$pageSize;
            if ($pageSize < 1 || $pageSize > 500) {
                throw new \InvalidArgumentException('page_size 必须是 1 到 500 的整数');
            }
        }
        $this->appToken = $appToken;
        $this->tableId = $tableId;
        $this->filter = $filter;
        $this->pageSize = $pageSize;
        $this->pageToken = $pageToken === null || $pageToken === '' ? null : (string)$pageToken;
    }

    /**
     * fieldNames 指定返回的字段名
     * @param array $fieldNames 字段名列表
     * @return $this
     */
    public function fieldNames(array $fieldNames)
    {
        $this->fieldNames = array_values($fieldNames);

        return $this;
    }

    /**
     * sort 设置排序
     * @param array $sort 排序条件
     * @return $this
     */
    public function sort(array $sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * viewId 限定视图
     * @param string $viewId 视图 ID
     * @return $this
     */
    public function viewId($viewId)
    {
        $this->viewId = (string)$viewId;

        return $this;
    }

    /**
     * automaticFields 是否返回系统字段
     * @param bool $automaticFields true 返回创建时间、修改时间、创建人、修改人
     * @return $this
     */
    public function automaticFields($automaticFields)
    {
        $this->automaticFields = (bool)$automaticFields;

        return $this;
    }

    /**
     * userIdType 用户 ID 类型
     * @param string $userIdType 用户 ID 类型
     * @return $this
     */
    public function userIdType($userIdType)
    {
        $this->userIdType = (string)$userIdType;

        return $this;
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
        return '/bitable/v1/apps/' . rawurlencode($this->appToken) . '/tables/' . rawurlencode($this->tableId) . '/records/search';
    }

    /**
     * getQuery 查询参数
     * @return array
     */
    public function getQuery()
    {
        $query = [];
        if ($this->userIdType !== null && $this->userIdType !== '') {
            $query['user_id_type'] = $this->userIdType;
        }
        if ($this->pageToken !== null) {
            $query['page_token'] = $this->pageToken;
        }
        if ($this->pageSize !== null) {
            $query['page_size'] = $this->pageSize;
        }

        return $query;
    }

    /**
     * getBody 请求体
     * @return array
     */
    public function getBody()
    {
        $body = [];
        if ($this->viewId !== null && $this->viewId !== '') {
            $body['view_id'] = $this->viewId;
        }
        if ($this->fieldNames !== null) {
            $body['field_names'] = $this->fieldNames;
        }
        if ($this->sort !== null) {
            $body['sort'] = $this->sort;
        }
        if ($this->filter !== null) {
            $body['filter'] = $this->filter;
        }
        if ($this->automaticFields !== null) {
            $body['automatic_fields'] = $this->automaticFields;
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
