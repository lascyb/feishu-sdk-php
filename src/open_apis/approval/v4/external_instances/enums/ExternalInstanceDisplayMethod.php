<?php
declare(strict_types=1);

namespace feishu\open_apis\approval\v4\external_instances\enums;

enum ExternalInstanceDisplayMethod: string
{
    case Browser = 'BROWSER';
    case Sidebar = 'SIDEBAR';
    case Normal = 'NORMAL';
    case Trusteeship = 'TRUSTEESHIP';
}
