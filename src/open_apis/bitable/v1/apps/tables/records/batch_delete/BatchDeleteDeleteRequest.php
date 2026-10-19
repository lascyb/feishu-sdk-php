<?php

namespace feishu\open_apis\bitable\v1\apps\tables\records\batch_delete;

use feishu\Contract\RequestInterface;

/**
 * BatchDeleteDeleteRequest 删除多条记录
 * 文档：https://open.feishu.cn/document/server-docs/docs/bitable-v1/app-table-record/batch_delete
 * API：POST /open-apis/bitable/v1/apps/:app_token/tables/:table_id/records/batch_delete
 */
class BatchDeleteDeleteRequest implements RequestInterface
{
    /** @var string 多维表格 app_token */
    private $appToken;

    /** @var string 数据表 table_id */
    private $tableId;

    /** @var list<string> 待删除记录 ID，单次最多 500 条 */
    private $records;

    /** @var bool|null 是否忽略读写一致性检查 */
    private $ignoreConsistencyCheck;

    /**
     * __construct 构造删除多条记录请求
     * @param string $appToken 多维表格 app_token
     * @param string $tableId 数据表 table_id
     * @param array $records 记录 ID 列表
     * @param bool|null $ignoreConsistencyCheck 是否忽略读写一致性检查
     */
    public function __construct($appToken, $tableId, array $records, $ignoreConsistencyCheck = null)
    {
        $appToken = trim((string)$appToken);
        $tableId = trim((string)$tableId);
        if ($appToken === '' || $tableId === '') {
            throw new \InvalidArgumentException('app_token 与 table_id 不能为空');
        }
        $ids = [];
        foreach ($records as $recordId) {
            $recordId = trim((string)$recordId);
            if ($recordId === '') {
                throw new \InvalidArgumentException('records 不能包含空的记录 ID');
            }
            $ids[] = $recordId;
        }
        if ($ids === [] || count($ids) > 500) {
            throw new \InvalidArgumentException('records 数量必须是 1 到 500');
        }
        $this->appToken = $appToken;
        $this->tableId = $tableId;
        $this->records = $ids;
        $this->ignoreConsistencyCheck = $ignoreConsistencyCheck === null ? null : (bool)$ignoreConsistencyCheck;
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
        return '/bitable/v1/apps/' . rawurlencode($this->appToken) . '/tables/' . rawurlencode($this->tableId) . '/records/batch_delete';
    }

    /**
     * getQuery 查询参数
     * @return array
     */
    public function getQuery()
    {
        if ($this->ignoreConsistencyCheck === null) {
            return [];
        }

        return ['ignore_consistency_check' => $this->ignoreConsistencyCheck ? 'true' : 'false'];
    }

    /**
     * getBody 请求体
     * @return array
     */
    public function getBody()
    {
        return ['records' => $this->records];
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
