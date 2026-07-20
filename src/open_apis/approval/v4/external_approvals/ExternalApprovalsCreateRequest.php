<?php

namespace feishu\open_apis\approval\v4\external_approvals;

use feishu\Contract\RequestInterface;

/**
 * ExternalApprovalsCreateRequest 创建三方审批定义
 * 文档：https://open.feishu.cn/document/server-docs/approval-v4/external_approval/create
 * API：POST /open-apis/approval/v4/external_approvals
 */
class ExternalApprovalsCreateRequest implements RequestInterface
{
    /** @var string 三方审批定义名称 i18n Key */
    private $approvalName;

    /** @var string 应用自定义审批定义 Code */
    private $approvalCode;

    /** @var array 三方审批相关信息 */
    private $external;

    /** @var string|null 部门 ID 类型查询参数 {department_id,open_department_id} */
    private $departmentIdType;

    /** @var string|null 用户 ID 类型查询参数 {open_id,union_id,user_id} */
    private $userIdType;

    /** @var string|null 审批分组 Code */
    private $groupCode;

    /** @var string|null 审批分组名称 i18n Key */
    private $groupName;

    /** @var string|null 审批定义说明 i18n Key */
    private $description;

    /** @var array|null 国际化文案列表 */
    private $i18nResources;

    /** @var array|null 审批可见人列表 */
    private $viewers;

    /** @var array|null 审批流程管理员用户 ID 列表 */
    private $managers;

    /**
     * __construct 构造创建三方审批定义请求
     * @param string $approvalName 三方审批定义名称 i18n Key
     * @param string $approvalCode 应用自定义审批定义 Code
     * @param array $external 三方审批相关信息
     */
    public function __construct($approvalName, $approvalCode, array $external)
    {
        $this->approvalName = (string)$approvalName;
        $this->approvalCode = (string)$approvalCode;
        $this->external = $external;
    }

    /**
     * setDepartmentIdType 设置部门 ID 类型查询参数
     * @param string $departmentIdType 部门 ID 类型
     * @return self
     */
    public function setDepartmentIdType($departmentIdType)
    {
        $this->departmentIdType = self::nullableString($departmentIdType);

        return $this;
    }

    /**
     * setUserIdType 设置用户 ID 类型查询参数
     * @param string $userIdType 用户 ID 类型
     * @return self
     */
    public function setUserIdType($userIdType)
    {
        $this->userIdType = self::nullableString($userIdType);

        return $this;
    }

    /**
     * setGroupCode 设置审批分组 Code
     * @param string $groupCode 审批分组 Code
     * @return self
     */
    public function setGroupCode($groupCode)
    {
        $this->groupCode = self::nullableString($groupCode);

        return $this;
    }

    /**
     * setGroupName 设置审批分组名称 i18n Key
     * @param string $groupName 审批分组名称 i18n Key
     * @return self
     */
    public function setGroupName($groupName)
    {
        $this->groupName = self::nullableString($groupName);

        return $this;
    }

    /**
     * setDescription 设置审批定义说明 i18n Key
     * @param string $description 审批定义说明 i18n Key
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = self::nullableString($description);

        return $this;
    }

    /**
     * setI18nResources 设置国际化文案
     * @param array $i18nResources 国际化文案列表
     * @return self
     */
    public function setI18nResources(array $i18nResources)
    {
        $this->i18nResources = $i18nResources;

        return $this;
    }

    /**
     * setViewers 设置审批可见人列表
     * @param array $viewers 审批可见人列表
     * @return self
     */
    public function setViewers(array $viewers)
    {
        $this->viewers = $viewers;

        return $this;
    }

    /**
     * setManagers 设置审批流程管理员
     * @param array $managers 管理员用户 ID 列表
     * @return self
     */
    public function setManagers(array $managers)
    {
        $this->managers = $managers;

        return $this;
    }

    /**
     * nullableString 空字符串转 null
     * @param mixed $value
     * @return string|null
     */
    private static function nullableString($value)
    {
        return $value === null || $value === '' ? null : (string)$value;
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
        return '/approval/v4/external_approvals';
    }

    /**
     * getQuery 查询参数
     * @return array
     */
    public function getQuery()
    {
        $query = [];

        if ($this->departmentIdType !== null) {
            $query['department_id_type'] = $this->departmentIdType;
        }

        if ($this->userIdType !== null) {
            $query['user_id_type'] = $this->userIdType;
        }

        return $query;
    }

    /**
     * getBody 请求体
     * @return array
     */
    public function getBody()
    {
        $body = [
            'approval_name' => $this->approvalName,
            'approval_code' => $this->approvalCode,
            'external' => $this->external,
        ];

        if ($this->groupCode !== null) {
            $body['group_code'] = $this->groupCode;
        }

        if ($this->groupName !== null) {
            $body['group_name'] = $this->groupName;
        }

        if ($this->description !== null) {
            $body['description'] = $this->description;
        }

        if ($this->i18nResources !== null) {
            $body['i18n_resources'] = $this->i18nResources;
        }

        if ($this->viewers !== null) {
            $body['viewers'] = $this->viewers;
        }

        if ($this->managers !== null) {
            $body['managers'] = $this->managers;
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
