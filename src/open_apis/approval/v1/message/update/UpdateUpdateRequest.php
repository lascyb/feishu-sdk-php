<?php

namespace feishu\open_apis\approval\v1\message\update;

use feishu\Contract\RequestInterface;

/**
 * UpdateUpdateRequest 更新审批 Bot 消息
 * 文档：https://open.feishu.cn/document/server-docs/approval-v4/message/update-bot-messages
 * API：POST /open-apis/approval/v1/message/update
 */
class UpdateUpdateRequest implements RequestInterface
{
    /** @var string 待更新的审批 Bot 消息 ID */
    private $messageId;

    /** @var string 状态类型 {APPROVED:已同意,REJECTED:已拒绝,CANCELLED:已撤回,FORWARDED:已转交,ROLLBACK:已回退,ADD:已加签,DELETED:已删除,PROCESSED:已处理,CUSTOM:自定义} */
    private $status;

    /** @var array 国际化文案列表 */
    private $i18nResources;

    /** @var string|null 自定义状态时标题 i18n Key（status=CUSTOM 时使用，@i18n@ 开头） */
    private $statusName;

    /** @var string|null 自定义状态时查看详情按钮 i18n Key（status=CUSTOM 时使用，@i18n@ 开头） */
    private $detailActionName;

    /**
     * __construct 构造更新审批 Bot 消息请求
     * @param string $messageId 待更新的审批 Bot 消息 ID
     * @param string $status 状态类型
     * @param array $i18nResources 国际化文案，每项含 locale、is_default、texts
     */
    public function __construct($messageId, $status, array $i18nResources)
    {
        $this->messageId = (string)$messageId;
        $this->status = (string)$status;
        $this->i18nResources = $i18nResources;
    }

    /**
     * setStatusName 设置自定义状态标题 i18n Key
     * @param string $statusName 自定义状态标题 i18n Key
     * @return self
     */
    public function setStatusName($statusName)
    {
        $this->statusName = self::nullableString($statusName);

        return $this;
    }

    /**
     * setDetailActionName 设置自定义查看详情按钮 i18n Key
     * @param string $detailActionName 自定义查看详情按钮 i18n Key
     * @return self
     */
    public function setDetailActionName($detailActionName)
    {
        $this->detailActionName = self::nullableString($detailActionName);

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
        return '/approval/v1/message/update';
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
            'message_id' => $this->messageId,
            'status' => $this->status,
            'i18n_resources' => $this->i18nResources,
        ];

        if ($this->statusName !== null) {
            $body['status_name'] = $this->statusName;
        }

        if ($this->detailActionName !== null) {
            $body['detail_action_name'] = $this->detailActionName;
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
