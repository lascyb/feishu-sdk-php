<?php

declare(strict_types=1);

namespace feishu\open_apis\approval\v4\external_instances\builders;

use InvalidArgumentException;
use feishu\open_apis\approval\v4\external_instances\enums\ExternalInstanceTaskStatus;

final class ExternalInstanceTaskBuilder
{
    public const MAX_ITEMS = 300;

    /**
     * make 构建任务节点
     * @return array<string, mixed>
     */
    public static function make(
        string|int $taskId,
        ExternalInstanceTaskStatus $status,
        string $pcLink,
        string|int $createTime,
        string|int $updateTime,
        string|int $endTime = 0,
        ?string $userId = null,
        ?string $openId = null,
        ?string $mobileLink = null,
    ): array {
        self::assertAssignee($userId, $openId, $status);

        $task = [
            'task_id' => (string) $taskId,
            'status' => $status->value,
            'links' => ExternalInstanceLinkBuilder::make($pcLink, $mobileLink),
            'create_time' => (string) $createTime,
            'update_time' => (string) $updateTime,
            'end_time' => (string) $endTime,
        ];

        if ($userId !== null && $userId !== '') {
            $task['user_id'] = $userId;
        }
        if ($openId !== null && $openId !== '') {
            $task['open_id'] = $openId;
        }

        return $task;
    }

    public static function pending(
        string|int $taskId,
        string $pcLink,
        string|int $createTime,
        string|int $updateTime,
        ?string $userId = null,
        ?string $openId = null,
        ?string $mobileLink = null,
    ): array {
        return self::make(
            taskId: $taskId,
            status: ExternalInstanceTaskStatus::Pending,
            pcLink: $pcLink,
            createTime: $createTime,
            updateTime: $updateTime,
            endTime: 0,
            userId: $userId,
            openId: $openId,
            mobileLink: $mobileLink,
        );
    }

    public static function approved(
        string|int $taskId,
        string $pcLink,
        string|int $createTime,
        string|int $updateTime,
        string|int $endTime,
        ?string $userId = null,
        ?string $openId = null,
        ?string $mobileLink = null,
    ): array {
        return self::make(
            taskId: $taskId,
            status: ExternalInstanceTaskStatus::Approved,
            pcLink: $pcLink,
            createTime: $createTime,
            updateTime: $updateTime,
            endTime: $endTime,
            userId: $userId,
            openId: $openId,
            mobileLink: $mobileLink,
        );
    }

    public static function rejected(
        string|int $taskId,
        string $pcLink,
        string|int $createTime,
        string|int $updateTime,
        string|int $endTime,
        ?string $userId = null,
        ?string $openId = null,
        ?string $mobileLink = null,
    ): array {
        return self::make(
            taskId: $taskId,
            status: ExternalInstanceTaskStatus::Rejected,
            pcLink: $pcLink,
            createTime: $createTime,
            updateTime: $updateTime,
            endTime: $endTime,
            userId: $userId,
            openId: $openId,
            mobileLink: $mobileLink,
        );
    }

    public static function transferred(
        string|int $taskId,
        string $pcLink,
        string|int $createTime,
        string|int $updateTime,
        string|int $endTime,
        ?string $userId = null,
        ?string $openId = null,
        ?string $mobileLink = null,
    ): array {
        return self::make(
            taskId: $taskId,
            status: ExternalInstanceTaskStatus::Transferred,
            pcLink: $pcLink,
            createTime: $createTime,
            updateTime: $updateTime,
            endTime: $endTime,
            userId: $userId,
            openId: $openId,
            mobileLink: $mobileLink,
        );
    }

    public static function done(
        string|int $taskId,
        string $pcLink,
        string|int $createTime,
        string|int $updateTime,
        string|int $endTime,
        ?string $userId = null,
        ?string $openId = null,
        ?string $mobileLink = null,
    ): array {
        return self::make(
            taskId: $taskId,
            status: ExternalInstanceTaskStatus::Done,
            pcLink: $pcLink,
            createTime: $createTime,
            updateTime: $updateTime,
            endTime: $endTime,
            userId: $userId,
            openId: $openId,
            mobileLink: $mobileLink,
        );
    }

    /**
     * assertListLimit 校验任务列表长度
     * @param array<int, array<string, mixed>> $taskList 任务列表
     * @return void
     */
    public static function assertListLimit(array $taskList): void
    {
        if (count($taskList) > self::MAX_ITEMS) {
            throw new InvalidArgumentException('task_list 最多 300 项');
        }
    }

    private static function assertAssignee(?string $userId, ?string $openId, ExternalInstanceTaskStatus $status): void
    {
        if (!$status->requiresAssignee()) {
            return;
        }

        if (($userId === null || $userId === '') && ($openId === null || $openId === '')) {
            throw new InvalidArgumentException('待办任务 userId 和 openId 至少需要传一个');
        }
    }
}
