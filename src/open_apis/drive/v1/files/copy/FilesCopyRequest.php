<?php

namespace feishu\open_apis\drive\v1\files\copy;

use feishu\Contract\RequestInterface;

/**
 * FilesCopyRequest 复制文件
 * 文档：https://open.feishu.cn/document/server-docs/docs/drive-v1/file/copy
 * API：POST /open-apis/drive/v1/files/:file_token/copy
 */
class FilesCopyRequest implements RequestInterface
{
    /** @var string 被复制的源文件 token */
    private $fileToken;

    /** @var string 复制后的文件名 */
    private $name;

    /** @var string 源文件类型 {file:文件,doc:旧版文档,sheet:电子表格,bitable:多维表格,docx:新版文档,mindnote:思维笔记,slides:幻灯片} */
    private $type;

    /** @var string 目标文件夹 token */
    private $folderToken;

    /** @var string|null 用户 ID 类型 {open_id:Open ID,union_id:Union ID,user_id:User ID} */
    private $userIdType;

    /** @var array<array{key: string, value: string}> 自定义复制参数 */
    private $extra;

    /**
     * __construct 构造复制文件请求
     * @param string $fileToken 被复制的源文件 token
     * @param string $name 复制后的文件名，最大 256 字节
     * @param string $type 源文件类型
     * @param string $folderToken 目标文件夹 token
     * @param string|null $userIdType 用户 ID 类型
     * @param array<array{key: string, value: string}> $extra 自定义复制参数
     */
    public function __construct(
        $fileToken,
        $name,
        $type,
        $folderToken,
        $userIdType = null,
        array $extra = []
    ) {
        $this->fileToken = (string)$fileToken;
        $this->name = (string)$name;
        $this->type = (string)$type;
        $this->folderToken = (string)$folderToken;
        $this->userIdType = $userIdType === null ? null : (string)$userIdType;
        $this->extra = $extra;
    }

    /**
     * sheet 复制电子表格
     * @param string $fileToken 被复制的电子表格 token
     * @param string $name 复制后的电子表格名
     * @param string $folderToken 目标文件夹 token
     * @return self
     */
    public static function sheet($fileToken, $name, $folderToken)
    {
        return new self($fileToken, $name, 'sheet', $folderToken);
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
     * getPath API 路径（含 file_token）
     * @return string
     */
    public function getPath()
    {
        return '/drive/v1/files/' . rawurlencode($this->fileToken) . '/copy';
    }

    /**
     * getQuery 查询参数
     * @return array
     */
    public function getQuery()
    {
        if ($this->userIdType === null || $this->userIdType === '') {
            return [];
        }

        return ['user_id_type' => $this->userIdType];
    }

    /**
     * getBody 请求体
     * @return array
     */
    public function getBody()
    {
        $body = [
            'name' => $this->name,
            'type' => $this->type,
            'folder_token' => $this->folderToken,
        ];
        if ($this->extra !== []) {
            $body['extra'] = $this->extra;
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
