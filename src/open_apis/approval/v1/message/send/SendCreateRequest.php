<?php

namespace feishu\open_apis\approval\v1\message\send;

use feishu\Contract\RequestInterface;

/**
 * SendCreateRequest 发送审批 Bot 消息
 * 文档：https://open.feishu.cn/document/server-docs/approval-v4/message/send-bot-messages
 * API：POST /open-apis/approval/v1/message/send
 */
class SendCreateRequest implements RequestInterface
{
    /** @var string 模板编号 */
    private $templateId;

    /** @var array 操作按钮列表 */
    private $actions;

    /** @var array 国际化文案列表 */
    private $i18nResources;

    /** @var string|null 接收用户 user_id（与 openId 至少传一个） */
    private $userId;

    /** @var string|null 接收用户 open_id（与 userId 至少传一个） */
    private $openId;

    /** @var string|null 幂等 ID */
    private $uuid;

    /** @var string|null 审批名称 i18n Key */
    private $approvalName;

    /** @var string|null 标题用户 ID */
    private $titleUserId;

    /** @var string|null 标题用户 ID 类型 {user_id,open_id} */
    private $titleUserIdType;

    /** @var string|null 评论 i18n Key */
    private $comment;

    /** @var array|null 消息内容 */
    private $content;

    /** @var string|null 备注 i18n Key */
    private $note;

    /** @var string|null 发送人 user_id */
    private $senderUserId;

    /** @var string|null 转发留言 */
    private $text;

    /** @var array|null 快捷审批回调配置 */
    private $actionCallback;

    /** @var array|null 快捷审批操作配置 */
    private $actionConfigs;

    /** @var string|null 自定义模板标题 i18n Key（template_id=1021） */
    private $customTitle;

    /** @var string|null 自定义模板内容 i18n Key（template_id=1021） */
    private $customContent;

    /**
     * __construct 构造发送审批 Bot 消息请求
     * @param string $templateId 模板编号
     * @param array $actions 操作按钮列表
     * @param array $i18nResources 国际化文案列表
     */
    public function __construct($templateId, array $actions, array $i18nResources)
    {
        $this->templateId = (string)$templateId;
        $this->actions = $actions;
        $this->i18nResources = $i18nResources;
    }

    /**
     * commonTemplate 通用模板快捷构造
     * @param string $templateId 模板编号
     * @param string $userId 接收用户 user_id
     * @param array $actions 操作按钮列表
     * @param array $i18nResources 国际化文案列表
     * @return self
     */
    public static function commonTemplate($templateId, $userId, array $actions, array $i18nResources): SendCreateRequest
    {
        return (new self($templateId, $actions, $i18nResources))->setUserId($userId);
    }

    /**
     * customTemplate 自定义模板快捷构造（template_id=1021）
     * @param string $userId 接收用户 user_id
     * @param string $customTitle 模板标题 i18n Key
     * @param string $customContent 模板内容 i18n Key
     * @param array $actions 操作按钮列表
     * @param array $i18nResources 国际化文案列表
     * @return self
     */
    public static function customTemplate(
        string $userId,
        string $customTitle,
        string $customContent,
        array  $actions,
        array  $i18nResources
    ): SendCreateRequest
    {
        return (new self('1021', $actions, $i18nResources))
            ->setUserId($userId)
            ->setCustomTitle($customTitle)
            ->setCustomContent($customContent);
    }

    /**
     * setUserId 设置接收用户 user_id
     * @param string $userId 用户 user_id
     * @return self
     */
    public function setUserId(string $userId): static
    {
        $this->userId = self::nullableString($userId);

        return $this;
    }

    /**
     * setOpenId 设置接收用户 open_id
     * @param string $openId 用户 open_id
     * @return self
     */
    public function setOpenId($openId)
    {
        $this->openId = self::nullableString($openId);

        return $this;
    }

    /**
     * setUuid 设置幂等 ID
     * @param string $uuid 幂等 ID
     * @return self
     */
    public function setUuid($uuid)
    {
        $this->uuid = self::nullableString($uuid);

        return $this;
    }

    /**
     * setApprovalName 设置审批名称 i18n Key
     * @param string $approvalName 审批名称 i18n Key
     * @return self
     */
    public function setApprovalName($approvalName)
    {
        $this->approvalName = self::nullableString($approvalName);

        return $this;
    }

    /**
     * setTitleUserId 设置标题用户 ID
     * @param string $titleUserId 标题用户 ID
     * @return self
     */
    public function setTitleUserId($titleUserId)
    {
        $this->titleUserId = self::nullableString($titleUserId);

        return $this;
    }

    /**
     * setTitleUserIdType 设置标题用户 ID 类型
     * @param string $titleUserIdType 标题用户 ID 类型 {user_id,open_id}
     * @return self
     */
    public function setTitleUserIdType($titleUserIdType)
    {
        $this->titleUserIdType = self::nullableString($titleUserIdType);

        return $this;
    }

    /**
     * setComment 设置评论 i18n Key
     * @param string $comment 评论 i18n Key
     * @return self
     */
    public function setComment($comment)
    {
        $this->comment = self::nullableString($comment);

        return $this;
    }

    /**
     * setContent 设置消息内容
     * @param array $content 消息内容
     * @return self
     */
    public function setContent(array $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * setNote 设置备注 i18n Key
     * @param string $note 备注 i18n Key
     * @return self
     */
    public function setNote($note)
    {
        $this->note = self::nullableString($note);

        return $this;
    }

    /**
     * setSenderUserId 设置发送人 user_id
     * @param string $senderUserId 发送人 user_id
     * @return self
     */
    public function setSenderUserId($senderUserId)
    {
        $this->senderUserId = self::nullableString($senderUserId);

        return $this;
    }

    /**
     * setText 设置转发留言
     * @param string $text 转发留言
     * @return self
     */
    public function setText($text)
    {
        $this->text = self::nullableString($text);

        return $this;
    }

    /**
     * setActionCallback 设置快捷审批回调配置
     * @param array $actionCallback 快捷审批回调配置
     * @return self
     */
    public function setActionCallback(array $actionCallback)
    {
        $this->actionCallback = $actionCallback;

        return $this;
    }

    /**
     * setActionConfigs 设置快捷审批操作配置
     * @param array $actionConfigs 快捷审批操作配置
     * @return self
     */
    public function setActionConfigs(array $actionConfigs)
    {
        $this->actionConfigs = $actionConfigs;

        return $this;
    }

    /**
     * setCustomTitle 设置自定义模板标题 i18n Key
     * @param string $customTitle 自定义模板标题 i18n Key
     * @return self
     */
    public function setCustomTitle($customTitle)
    {
        $this->customTitle = self::nullableString($customTitle);

        return $this;
    }

    /**
     * setCustomContent 设置自定义模板内容 i18n Key
     * @param string $customContent 自定义模板内容 i18n Key
     * @return self
     */
    public function setCustomContent($customContent)
    {
        $this->customContent = self::nullableString($customContent);

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
        return '/approval/v1/message/send';
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
            'template_id' => $this->templateId,
            'actions' => $this->actions,
            'i18n_resources' => $this->i18nResources,
        ];

        if ($this->userId !== null) {
            $body['user_id'] = $this->userId;
        }

        if ($this->openId !== null) {
            $body['open_id'] = $this->openId;
        }

        if ($this->uuid !== null) {
            $body['uuid'] = $this->uuid;
        }

        if ($this->approvalName !== null) {
            $body['approval_name'] = $this->approvalName;
        }

        if ($this->titleUserId !== null) {
            $body['title_user_id'] = $this->titleUserId;
        }

        if ($this->titleUserIdType !== null) {
            $body['title_user_id_type'] = $this->titleUserIdType;
        }

        if ($this->comment !== null) {
            $body['comment'] = $this->comment;
        }

        if ($this->content !== null) {
            $body['content'] = $this->content;
        }

        if ($this->note !== null) {
            $body['note'] = $this->note;
        }

        if ($this->senderUserId !== null) {
            $body['sender_user_id'] = $this->senderUserId;
        }

        if ($this->text !== null) {
            $body['text'] = $this->text;
        }

        if ($this->actionCallback !== null) {
            $body['action_callback'] = $this->actionCallback;
        }

        if ($this->actionConfigs !== null) {
            $body['action_configs'] = $this->actionConfigs;
        }

        if ($this->customTitle !== null) {
            $body['custom_title'] = $this->customTitle;
        }

        if ($this->customContent !== null) {
            $body['custom_content'] = $this->customContent;
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
