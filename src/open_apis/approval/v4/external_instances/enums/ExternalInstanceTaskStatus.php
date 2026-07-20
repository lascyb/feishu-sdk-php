<?php

declare(strict_types=1);

namespace feishu\open_apis\approval\v4\external_instances\enums;

enum ExternalInstanceTaskStatus: string
{
    case Pending = 'PENDING';
    case Approved = 'APPROVED';
    case Rejected = 'REJECTED';
    case Transferred = 'TRANSFERRED';
    case Done = 'DONE';

    public function requiresAssignee(): bool
    {
        return $this === self::Pending;
    }

    public function isTerminal(): bool
    {
        return match ($this) {
            self::Approved,
            self::Rejected,
            self::Transferred,
            self::Done => true,
            default => false,
        };
    }
}
