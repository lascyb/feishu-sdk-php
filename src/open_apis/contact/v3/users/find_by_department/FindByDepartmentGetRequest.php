<?php

namespace feishu\open_apis\contact\v3\users\find_by_department;

use feishu\Contract\RequestInterface;

/**
 * FindByDepartmentGetRequest 获取部门直属用户列表
 * 文档：https://open.feishu.cn/document/server-docs/contact-v3/user/find_by_department
 * API：GET /open-apis/contact/v3/users/find_by_department
 */
class FindByDepartmentGetRequest implements RequestInterface
{
    /** @var string 部门 ID，与 department_id_type 一致；根部门为 0 */
    private $departmentId;

    /** @var string|null 用户 ID 类型 {open_id:应用内用户ID,union_id:开发商下用户ID,user_id:租户内用户ID}，默认 open_id */
    private $userIdType;

    /** @var string|null 部门 ID 类型 {department_id:自定义部门ID,open_department_id:系统生成部门ID}，默认 open_department_id */
    private $departmentIdType;

    /** @var int|null 分页大小，最大 50，默认 10 */
    private $pageSize;

    /** @var string|null 分页标记，首次请求不传 */
    private $pageToken;

    /**
     * __construct 构造获取部门直属用户列表请求
     * @param string $departmentId 部门 ID
     * @param string|null $userIdType 用户 ID 类型
     * @param string|null $departmentIdType 部门 ID 类型
     * @param int|null $pageSize 分页大小
     * @param string|null $pageToken 分页标记
     */
    public function __construct(
        $departmentId,
        $userIdType = null,
        $departmentIdType = null,
        $pageSize = null,
        $pageToken = null
    ) {
        $this->departmentId = (string)$departmentId;
        $this->userIdType = $userIdType === null || $userIdType === ''
            ? null
            : (string)$userIdType;
        $this->departmentIdType = $departmentIdType === null || $departmentIdType === ''
            ? null
            : (string)$departmentIdType;
        $this->pageSize = $pageSize === null || $pageSize === '' ? null : (int)$pageSize;
        $this->pageToken = $pageToken === null || $pageToken === ''
            ? null
            : (string)$pageToken;
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
        return '/contact/v3/users/find_by_department';
    }

    /**
     * getQuery 查询参数
     * @return array
     */
    public function getQuery()
    {
        $query = [
            'department_id' => $this->departmentId,
        ];

        if ($this->userIdType !== null) {
            $query['user_id_type'] = $this->userIdType;
        }
        if ($this->departmentIdType !== null) {
            $query['department_id_type'] = $this->departmentIdType;
        }
        if ($this->pageSize !== null) {
            $query['page_size'] = $this->pageSize;
        }
        if ($this->pageToken !== null) {
            $query['page_token'] = $this->pageToken;
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
