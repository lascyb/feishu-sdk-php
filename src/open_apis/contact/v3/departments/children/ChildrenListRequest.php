<?php

namespace feishu\open_apis\contact\v3\departments\children;

use feishu\Contract\RequestInterface;

/**
 * ChildrenListRequest 获取子部门列表
 * 文档：https://open.feishu.cn/document/server-docs/contact-v3/department/children
 * API：GET /open-apis/contact/v3/departments/:department_id/children
 */
class ChildrenListRequest implements RequestInterface
{
    /** @var string 部门 ID（路径参数，根部门为 "0"） */
    private $departmentId;

    /** @var string|null 用户 ID 类型 {open_id:应用内用户ID,union_id:开发商下用户ID,user_id:租户内用户ID} */
    private $userIdType;

    /** @var string|null 部门 ID 类型 {department_id:自定义部门ID,open_department_id:系统生成的部门ID} */
    private $departmentIdType;

    /** @var bool|null 是否递归获取子部门 */
    private $fetchChild;

    /** @var int|null 分页大小，最大 50 */
    private $pageSize;

    /** @var string|null 分页标识 */
    private $pageToken;

    /**
     * __construct 构造获取子部门列表请求
     * @param string $departmentId 部门 ID（根部门为 "0"）
     * @param string|null $userIdType 用户 ID 类型
     * @param string|null $departmentIdType 部门 ID 类型
     * @param bool|null $fetchChild 是否递归获取子部门
     * @param int|null $pageSize 分页大小
     * @param string|null $pageToken 分页标识
     */
    public function __construct(
        $departmentId,
        $userIdType = null,
        $departmentIdType = null,
        $fetchChild = null,
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
        $this->fetchChild = $fetchChild === null ? null : (bool)$fetchChild;
        $this->pageSize = $pageSize === null ? null : (int)$pageSize;
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
        return '/contact/v3/departments/' . rawurlencode($this->departmentId) . '/children';
    }

    /**
     * getQuery 查询参数
     * @return array
     */
    public function getQuery()
    {
        $query = [];

        if ($this->userIdType !== null) {
            $query['user_id_type'] = $this->userIdType;
        }
        if ($this->departmentIdType !== null) {
            $query['department_id_type'] = $this->departmentIdType;
        }
        if ($this->fetchChild !== null) {
            $query['fetch_child'] = $this->fetchChild ? 'true' : 'false';
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
