<?php

declare(strict_types=1);

namespace feishu\open_apis\approval\v4\external_instances\enums;

enum ExternalInstanceUpdateMode: string
{
    case Replace = 'REPLACE';
    case Update = 'UPDATE';
}
