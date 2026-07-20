<?php

declare(strict_types=1);

namespace feishu\open_apis\approval\v4\external_instances\enums;

enum ExternalInstanceCcReadStatus: string
{
    case Read = 'READ';
    case Unread = 'UNREAD';
}
