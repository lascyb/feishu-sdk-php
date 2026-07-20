<?php

namespace feishu\open_apis\approval\v4\external_instances;

use feishu\Contract\RequestInterface;

/**
 * ExternalInstancesCreateRequest 同步三方审批实例
 * 文档：https://open.feishu.cn/document/server-docs/approval-v4/external_instance/create
 * API：POST /open-apis/approval/v4/external_instances
 */
class ExternalInstancesCreateRequest implements RequestInterface
{
    /** @var string 审批定义 Code */
    private $approvalCode;

    /** @var string 审批实例状态 {PENDING,APPROVED,REJECTED,CANCELED,DELETED,HIDDEN,TERMINATED} */
    private $status;

    /** @var string 审批实例唯一标识 */
    private $instanceId;

    /** @var array 审批实例链接信息 */
    private $links;

    /** @var string 审批发起时间（Unix 毫秒时间戳） */
    private $startTime;

    /** @var string 审批实例结束时间（Unix 毫秒时间戳，未结束为 0） */
    private $endTime;

    /** @var string 审批实例最近更新时间（Unix 毫秒时间戳） */
    private $updateTime;

    /** @var array 国际化文案列表 */
    private $i18nResources;

    /** @var string|null 审批发起人 user_id */
    private $userId;

    /** @var string|null 审批发起人 open_id */
    private $openId;

    /** @var string|null 审批实例扩展参数 JSON 字符串 */
    private $extra;

    /** @var string|null 审批展示名称 i18n Key */
    private $title;

    /** @var array|null 表单数据 */
    private $form;

    /** @var string|null 审批发起人用户名 i18n Key */
    private $userName;

    /** @var string|null 发起人部门 ID */
    private $departmentId;

    /** @var string|null 发起人部门名称 i18n Key */
    private $departmentName;

    /** @var string|null 列表页打开方式 {BROWSER,SIDEBAR,NORMAL,TRUSTEESHIP} */
    private $displayMethod;

    /** @var string|null 更新方式 {REPLACE,UPDATE} */
    private $updateMode;

    /** @var array|null 任务列表 */
    private $taskList;

    /** @var array|null 抄送列表 */
    private $ccList;

    /** @var string|null 单据托管认证 token */
    private $trusteeshipUrlToken;

    /** @var string|null 托管用户 ID 类型 */
    private $trusteeshipUserIdType;

    /** @var array|null 单据托管回调 URL */
    private $trusteeshipUrls;

    /** @var array|null 托管预缓存策略 */
    private $trusteeshipCacheConfig;

    /** @var string|null 资源所在地区 */
    private $resourceRegion;

    /**
     * __construct 构造同步三方审批实例请求
     * @param string $approvalCode 审批定义 Code
     * @param string $status 审批实例状态
     * @param string $instanceId 审批实例唯一标识
     * @param array $links 审批实例链接信息
     * @param string $startTime 审批发起时间（Unix 毫秒时间戳）
     * @param string $endTime 审批实例结束时间（Unix 毫秒时间戳）
     * @param string $updateTime 审批实例最近更新时间（Unix 毫秒时间戳）
     * @param array $i18nResources 国际化文案列表
     */
    public function __construct(
        $approvalCode,
        $status,
        $instanceId,
        array $links,
        $startTime,
        $endTime,
        $updateTime,
        array $i18nResources
    ) {
        $this->approvalCode = (string)$approvalCode;
        $this->status = (string)$status;
        $this->instanceId = (string)$instanceId;
        $this->links = $links;
        $this->startTime = (string)$startTime;
        $this->endTime = (string)$endTime;
        $this->updateTime = (string)$updateTime;
        $this->i18nResources = $i18nResources;
    }

    /**
     * withUserId 指定审批发起人 user_id 快捷构造
     * @param string $approvalCode 审批定义 Code
     * @param string $status 审批实例状态
     * @param string $instanceId 审批实例唯一标识
     * @param array $links 审批实例链接信息
     * @param string $startTime 审批发起时间
     * @param string $endTime 审批实例结束时间
     * @param string $updateTime 审批实例最近更新时间
     * @param array $i18nResources 国际化文案列表
     * @param string $userId 审批发起人 user_id
     * @return self
     */
    public static function withUserId(
        $approvalCode,
        $status,
        $instanceId,
        array $links,
        $startTime,
        $endTime,
        $updateTime,
        array $i18nResources,
        $userId
    ) {
        return (new self($approvalCode, $status, $instanceId, $links, $startTime, $endTime, $updateTime, $i18nResources))
            ->setUserId($userId);
    }

    /**
     * withOpenId 指定审批发起人 open_id 快捷构造
     * @param string $approvalCode 审批定义 Code
     * @param string $status 审批实例状态
     * @param string $instanceId 审批实例唯一标识
     * @param array $links 审批实例链接信息
     * @param string $startTime 审批发起时间
     * @param string $endTime 审批实例结束时间
     * @param string $updateTime 审批实例最近更新时间
     * @param array $i18nResources 国际化文案列表
     * @param string $openId 审批发起人 open_id
     * @return self
     */
    public static function withOpenId(
        $approvalCode,
        $status,
        $instanceId,
        array $links,
        $startTime,
        $endTime,
        $updateTime,
        array $i18nResources,
        $openId
    ) {
        return (new self($approvalCode, $status, $instanceId, $links, $startTime, $endTime, $updateTime, $i18nResources))
            ->setOpenId($openId);
    }

    /**
     * setUserId 设置审批发起人 user_id
     * @param string $userId 审批发起人 user_id
     * @return self
     */
    public function setUserId($userId)
    {
        $this->userId = self::nullableString($userId);

        return $this;
    }

    /**
     * setOpenId 设置审批发起人 open_id
     * @param string $openId 审批发起人 open_id
     * @return self
     */
    public function setOpenId($openId)
    {
        $this->openId = self::nullableString($openId);

        return $this;
    }

    /**
     * setExtra 设置审批实例扩展参数
     * @param string $extra 扩展参数 JSON 字符串
     * @return self
     */
    public function setExtra($extra)
    {
        $this->extra = self::nullableString($extra);

        return $this;
    }

    /**
     * setTitle 设置审批展示名称 i18n Key
     * @param string $title 审批展示名称 i18n Key
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = self::nullableString($title);

        return $this;
    }

    /**
     * setForm 设置表单数据
     * @param array $form 表单数据
     * @return self
     */
    public function setForm(array $form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * setUserName 设置审批发起人用户名 i18n Key
     * @param string $userName 审批发起人用户名 i18n Key
     * @return self
     */
    public function setUserName($userName)
    {
        $this->userName = self::nullableString($userName);

        return $this;
    }

    /**
     * setDepartmentId 设置发起人部门 ID
     * @param string $departmentId 发起人部门 ID
     * @return self
     */
    public function setDepartmentId($departmentId)
    {
        $this->departmentId = $departmentId === null ? null : (string)$departmentId;

        return $this;
    }

    /**
     * setDepartmentName 设置发起人部门名称 i18n Key
     * @param string $departmentName 发起人部门名称 i18n Key
     * @return self
     */
    public function setDepartmentName($departmentName)
    {
        $this->departmentName = self::nullableString($departmentName);

        return $this;
    }

    /**
     * setDisplayMethod 设置列表页打开方式
     * @param string $displayMethod 打开方式
     * @return self
     */
    public function setDisplayMethod($displayMethod)
    {
        $this->displayMethod = self::nullableString($displayMethod);

        return $this;
    }

    /**
     * setUpdateMode 设置更新方式
     * @param string $updateMode 更新方式 {REPLACE,UPDATE}
     * @return self
     */
    public function setUpdateMode($updateMode)
    {
        $this->updateMode = self::nullableString($updateMode);

        return $this;
    }

    /**
     * setTaskList 设置任务列表
     * @param array $taskList 任务列表
     * @return self
     */
    public function setTaskList(array $taskList)
    {
        $this->taskList = $taskList;

        return $this;
    }

    /**
     * setCcList 设置抄送列表
     * @param array $ccList 抄送列表
     * @return self
     */
    public function setCcList(array $ccList)
    {
        $this->ccList = $ccList;

        return $this;
    }

    /**
     * setTrusteeshipUrlToken 设置单据托管认证 token
     * @param string $trusteeshipUrlToken 单据托管认证 token
     * @return self
     */
    public function setTrusteeshipUrlToken($trusteeshipUrlToken)
    {
        $this->trusteeshipUrlToken = self::nullableString($trusteeshipUrlToken);

        return $this;
    }

    /**
     * setTrusteeshipUserIdType 设置托管用户 ID 类型
     * @param string $trusteeshipUserIdType 托管用户 ID 类型
     * @return self
     */
    public function setTrusteeshipUserIdType($trusteeshipUserIdType)
    {
        $this->trusteeshipUserIdType = self::nullableString($trusteeshipUserIdType);

        return $this;
    }

    /**
     * setTrusteeshipUrls 设置单据托管回调 URL
     * @param array $trusteeshipUrls 单据托管回调 URL
     * @return self
     */
    public function setTrusteeshipUrls(array $trusteeshipUrls)
    {
        $this->trusteeshipUrls = $trusteeshipUrls;

        return $this;
    }

    /**
     * setTrusteeshipCacheConfig 设置托管预缓存策略
     * @param array $trusteeshipCacheConfig 托管预缓存策略
     * @return self
     */
    public function setTrusteeshipCacheConfig(array $trusteeshipCacheConfig)
    {
        $this->trusteeshipCacheConfig = $trusteeshipCacheConfig;

        return $this;
    }

    /**
     * setResourceRegion 设置资源所在地区
     * @param string $resourceRegion 资源所在地区
     * @return self
     */
    public function setResourceRegion($resourceRegion)
    {
        $this->resourceRegion = self::nullableString($resourceRegion);

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
        return '/approval/v4/external_instances';
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
            'approval_code' => $this->approvalCode,
            'status' => $this->status,
            'instance_id' => $this->instanceId,
            'links' => $this->links,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'update_time' => $this->updateTime,
            'i18n_resources' => $this->i18nResources,
        ];

        if ($this->extra !== null) {
            $body['extra'] = $this->extra;
        }

        if ($this->title !== null) {
            $body['title'] = $this->title;
        }

        if ($this->form !== null) {
            $body['form'] = $this->form;
        }

        if ($this->userId !== null) {
            $body['user_id'] = $this->userId;
        }

        if ($this->openId !== null) {
            $body['open_id'] = $this->openId;
        }

        if ($this->userName !== null) {
            $body['user_name'] = $this->userName;
        }

        if ($this->departmentId !== null) {
            $body['department_id'] = $this->departmentId;
        }

        if ($this->departmentName !== null) {
            $body['department_name'] = $this->departmentName;
        }

        if ($this->displayMethod !== null) {
            $body['display_method'] = $this->displayMethod;
        }

        if ($this->updateMode !== null) {
            $body['update_mode'] = $this->updateMode;
        }

        if ($this->taskList !== null) {
            $body['task_list'] = $this->taskList;
        }

        if ($this->ccList !== null) {
            $body['cc_list'] = $this->ccList;
        }

        if ($this->trusteeshipUrlToken !== null) {
            $body['trusteeship_url_token'] = $this->trusteeshipUrlToken;
        }

        if ($this->trusteeshipUserIdType !== null) {
            $body['trusteeship_user_id_type'] = $this->trusteeshipUserIdType;
        }

        if ($this->trusteeshipUrls !== null) {
            $body['trusteeship_urls'] = $this->trusteeshipUrls;
        }

        if ($this->trusteeshipCacheConfig !== null) {
            $body['trusteeship_cache_config'] = $this->trusteeshipCacheConfig;
        }

        if ($this->resourceRegion !== null) {
            $body['resource_region'] = $this->resourceRegion;
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
