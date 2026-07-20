<?php

namespace feishu\open_apis\im\v1\messages;

use feishu\Contract\RequestInterface;

/**
 * MessagesCreateRequest 发送消息
 * 文档：https://open.feishu.cn/document/server-docs/im-v1/message/create
 * API：POST /open-apis/im/v1/messages
 */
class MessagesCreateRequest implements RequestInterface
{
    /** @var string 消息接收者 ID 类型 {open_id:用户OpenID,user_id:用户ID,union_id:用户UnionID,email:邮箱,chat_id:群ID} */
    private $receiveIdType;

    /** @var string 消息接收者 ID */
    private $receiveId;

    /** @var string 消息类型 {text:文本,post:富文本,interactive:卡片,image:图片,...} */
    private $msgType;

    /** @var array|string 消息内容（数组将在请求时 json_encode 为 content 字符串） */
    private $content;

    /** @var string|null 去重 uuid */
    private $uuid;

    /**
     * __construct 构造发送消息请求
     * @param string $receiveIdType 接收者 ID 类型
     * @param string $receiveId 接收者 ID
     * @param string $msgType 消息类型
     * @param array|string $content 消息内容
     * @param string|null $uuid 去重 uuid（可选）
     */
    public function __construct($receiveIdType, $receiveId, $msgType, $content, $uuid = null)
    {
        $this->receiveIdType = (string)$receiveIdType;
        $this->receiveId = (string)$receiveId;
        $this->msgType = (string)$msgType;
        $this->content = $content;
        $this->uuid = $uuid === null || $uuid === '' ? null : (string)$uuid;
    }

    /**
     * text 发送文本消息
     * @param string $receiveIdType 接收者 ID 类型
     * @param string $receiveId 接收者 ID
     * @param string $text 文本内容
     * @param string|null $uuid 去重 uuid
     * @return self
     */
    public static function text($receiveIdType, $receiveId, $text, $uuid = null)
    {
        return new self($receiveIdType, $receiveId, 'text', ['text' => (string)$text], $uuid);
    }

    /**
     * interactive 发送卡片消息（content 为卡片 JSON 结构）
     * @param string $receiveIdType 接收者 ID 类型
     * @param string $receiveId 接收者 ID
     * @param array $card 卡片内容（将序列化为 content 字符串）
     * @param string|null $uuid 去重 uuid
     * @return self
     */
    public static function interactive($receiveIdType, $receiveId, array $card, $uuid = null)
    {
        return new self($receiveIdType, $receiveId, 'interactive', $card, $uuid);
    }

    /**
     * template 发送卡片模板消息（搭建工具发布的模板，msg_type=interactive）
     * @param string $receiveIdType 接收者 ID 类型
     * @param string $receiveId 接收者 ID
     * @param string $templateId 卡片模板 ID
     * @param array $templateVariable 模板变量键值对
     * @param string $version 模板版本号，空则使用最新版本
     * @param string|null $uuid 去重 uuid
     * @return self
     */
    public static function template(
        $receiveIdType,
        $receiveId,
        $templateId,
        array $templateVariable = [],
        $version = '',
        $uuid = null
    ) {
        $data = [
            'template_id' => (string)$templateId,
            'template_variable' => $templateVariable,
        ];
        if ($version !== '') {
            $data['template_version_name'] = (string)$version;
        }

        return new self($receiveIdType, $receiveId, 'interactive', [
            'type' => 'template',
            'data' => $data,
        ], $uuid);
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
        return '/im/v1/messages';
    }

    /**
     * getQuery 查询参数
     * @return array
     */
    public function getQuery()
    {
        return [
            'receive_id_type' => $this->receiveIdType,
        ];
    }

    /**
     * getBody 请求体
     * @return array
     */
    public function getBody()
    {
        $content = is_array($this->content)
            ? json_encode($this->content, JSON_UNESCAPED_UNICODE)
            : (string)$this->content;

        $body = [
            'receive_id' => $this->receiveId,
            'msg_type' => $this->msgType,
            'content' => $content,
        ];

        if ($this->uuid !== null) {
            $body['uuid'] = $this->uuid;
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
