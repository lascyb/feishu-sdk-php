<?php

namespace feishu\open_apis\bitable\v1\apps\tables\records\batch_create;

use feishu\Contract\RequestInterface;

/**
 * BatchCreateCreateRequest 新增多条记录
 * 文档：https://open.feishu.cn/document/server-docs/docs/bitable-v1/app-table-record/batch_create
 * API：POST /open-apis/bitable/v1/apps/:app_token/tables/:table_id/records/batch_create
 */
class BatchCreateCreateRequest implements RequestInterface
{
    /** @var string 多维表格 app_token */
    private $appToken;

    /** @var string 数据表 table_id */
    private $tableId;

    /** @var array<array{fields: array}> 待新增记录，单次最多 500 条 */
    private $records;

    /** @var string|null 用户 ID 类型 {open_id:Open ID,union_id:Union ID,user_id:User ID} */
    private $userIdType;

    /** @var string|null 幂等标识，标准 uuidv4 */
    private $clientToken;

    /** @var bool|null 是否忽略读写一致性检查 */
    private $ignoreConsistencyCheck;

    /**
     * __construct 构造新增多条记录请求
     * @param string $appToken 多维表格 app_token
     * @param string $tableId 数据表 table_id
     * @param array $records 记录列表，每项含 fields
     * @param string|null $userIdType 用户 ID 类型
     * @param string|null $clientToken 幂等标识
     * @param bool|null $ignoreConsistencyCheck 是否忽略读写一致性检查
     */
    public function __construct(
        $appToken,
        $tableId,
        array $records,
        $userIdType = null,
        $clientToken = null,
        $ignoreConsistencyCheck = null
    ) {
        $appToken = trim((string)$appToken);
        $tableId = trim((string)$tableId);
        if ($appToken === '' || $tableId === '') {
            throw new \InvalidArgumentException('app_token 与 table_id 不能为空');
        }
        if ($records === [] || count($records) > 500) {
            throw new \InvalidArgumentException('records 数量必须是 1 到 500');
        }
        foreach ($records as $record) {
            if (!is_array($record) || !isset($record['fields']) || !is_array($record['fields'])) {
                throw new \InvalidArgumentException('records 每项必须包含 fields');
            }
        }
        $this->appToken = $appToken;
        $this->tableId = $tableId;
        $this->records = $records;
        $this->userIdType = $userIdType === null || $userIdType === '' ? null : (string)$userIdType;
        $this->clientToken = $clientToken === null || $clientToken === '' ? null : (string)$clientToken;
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
        return '/bitable/v1/apps/' . rawurlencode($this->appToken) . '/tables/' . rawurlencode($this->tableId) . '/records/batch_create';
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
        if ($this->clientToken !== null) {
            $query['client_token'] = $this->clientToken;
        }
        if ($this->ignoreConsistencyCheck !== null) {
            $query['ignore_consistency_check'] = $this->ignoreConsistencyCheck ? 'true' : 'false';
        }

        return $query;
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
