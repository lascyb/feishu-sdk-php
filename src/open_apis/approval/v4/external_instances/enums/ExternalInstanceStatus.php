<?php

declare(strict_types=1);

namespace feishu\open_apis\approval\v4\external_instances\enums;

enum ExternalInstanceStatus: string
{
    case Pending = 'PENDING';
    case Approved = 'APPROVED';
    case Rejected = 'REJECTED';
    case Canceled = 'CANCELED';
    case Deleted = 'DELETED';
    case Hidden = 'HIDDEN';
    case Terminated = 'TERMINATED';

    public function isTerminal(): bool
    {
        return match ($this) {
            self::Approved,
            self::Rejected,
            self::Canceled,
            self::Deleted,
            self::Terminated => true,
            default => false,
        };
    }
}
