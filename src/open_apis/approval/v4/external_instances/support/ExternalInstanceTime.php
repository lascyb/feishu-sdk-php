<?php

declare(strict_types=1);

namespace feishu\open_apis\approval\v4\external_instances\support;

use DateTimeInterface;
use feishu\open_apis\approval\v4\external_instances\enums\ExternalInstanceStatus;

final class ExternalInstanceTime
{
    public static function nowMs(): int
    {
        return (int) round(microtime(true) * 1000);
    }

    public static function toMs(null|string|int|DateTimeInterface $time): int
    {
        if ($time === null || $time === '') {
            return 0;
        }

        if ($time instanceof DateTimeInterface) {
            return $time->getTimestamp() * 1000;
        }

        if (is_int($time)) {
            return $time;
        }

        if (is_numeric($time)) {
            return (int) $time;
        }

        $timestamp = strtotime($time);

        return $timestamp === false ? 0 : $timestamp * 1000;
    }

    public static function nextUpdateTime(int $lastUpdateTime = 0): int
    {
        return max(self::nowMs(), $lastUpdateTime + 1);
    }

    public static function endTimeByStatus(
        ExternalInstanceStatus $status,
        null|string|int|DateTimeInterface $endTime = null
    ): int {
        if (!$status->isTerminal()) {
            return 0;
        }

        return self::toMs($endTime) ?: self::nowMs();
    }
}
