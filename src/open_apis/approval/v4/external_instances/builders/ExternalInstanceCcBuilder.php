<?php

declare(strict_types=1);

namespace feishu\open_apis\approval\v4\external_instances\builders;

use InvalidArgumentException;
use JsonException;
use feishu\open_apis\approval\v4\external_instances\enums\ExternalInstanceCcReadStatus;
use feishu\open_apis\approval\v4\external_instances\enums\ExternalInstanceDisplayMethod;

final class ExternalInstanceCcBuilder
{
    public const MAX_ITEMS = 200;

    /**
     * make 构建抄送节点
     * @param string|int $ccId 抄送 ID
     * @param string $pcLink PC 端链接
     * @param string|int $createTime 创建时间毫秒
     * @param string|int $updateTime 更新时间毫秒
     * @param ExternalInstanceCcReadStatus $readStatus 阅读状态
     * @param string|null $mobileLink 移动端链接
     * @param string|null $userId 抄送人 user_id
     * @param string|null $openId 抄送人 open_id
     * @param string|null $title 抄送标题
     * @param array<string, mixed>|string|null $extra 扩展参数
     * @param ExternalInstanceDisplayMethod|null $displayMethod 打开方式
     * @return array<string, mixed>
     * @throws JsonException
     */
    public static function make(
        string|int $ccId,
        string $pcLink,
        string|int $createTime,
        string|int $updateTime,
        ExternalInstanceCcReadStatus $readStatus,
        ?string $mobileLink = null,
        ?string $userId = null,
        ?string $openId = null,
        ?string $title = null,
        array|string|null $extra = null,
        ?ExternalInstanceDisplayMethod $displayMethod = null,
    ): array {
        self::assertAssignee($userId, $openId);

        $cc = [
            'cc_id' => (string) $ccId,
            'links' => ExternalInstanceLinkBuilder::make($pcLink, $mobileLink),
            'read_status' => $readStatus->value,
            'create_time' => (string) $createTime,
            'update_time' => (string) $updateTime,
        ];

        if ($userId !== null && $userId !== '') {
            $cc['user_id'] = $userId;
        }
        if ($openId !== null && $openId !== '') {
            $cc['open_id'] = $openId;
        }
        if ($title !== null && $title !== '') {
            $cc['title'] = $title;
        }
        if ($extra !== null && $extra !== '') {
            $cc['extra'] = is_array($extra)
                ? json_encode($extra, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
                : $extra;
        }
        if ($displayMethod !== null) {
            $cc['display_method'] = $displayMethod->value;
        }

        return $cc;
    }

    /**
     * assertListLimit 校验抄送列表长度
     * @param array<int, array<string, mixed>> $ccList 抄送列表
     * @return void
     */
    public static function assertListLimit(array $ccList): void
    {
        if (count($ccList) > self::MAX_ITEMS) {
            throw new InvalidArgumentException('cc_list 最多 200 项');
        }
    }

    /**
     * assertAssignee 校验抄送人标识
     * @param string|null $userId 抄送人 user_id
     * @param string|null $openId 抄送人 open_id
     * @return void
     */
    private static function assertAssignee(?string $userId, ?string $openId): void
    {
        if (($userId === null || $userId === '') && ($openId === null || $openId === '')) {
            throw new InvalidArgumentException('抄送节点 userId 和 openId 至少需要传一个');
        }
    }
}
