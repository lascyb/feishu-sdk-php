<?php

namespace feishu\Channel;

/**
 * 事件相关常量与工具（占位）
 */
class Events
{
    public const TYPE_MESSAGE = 'message';
    public const TYPE_CARD = 'card';
    public const TYPE_SYSTEM = 'system';

    public static function isMessage(array $event): bool
    {
        return isset($event['type']) && $event['type'] === self::TYPE_MESSAGE;
    }
}
