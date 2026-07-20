<?php

declare(strict_types=1);

namespace feishu\open_apis\approval\v4\external_instances;

use feishu\open_apis\approval\v4\external_instances\builders\ExternalInstanceBuilder;
use feishu\open_apis\approval\v4\external_instances\builders\ExternalInstanceCcBuilder;
use feishu\open_apis\approval\v4\external_instances\builders\ExternalInstanceFormBuilder;
use feishu\open_apis\approval\v4\external_instances\builders\ExternalInstanceI18nBuilder;
use feishu\open_apis\approval\v4\external_instances\builders\ExternalInstanceLinkBuilder;
use feishu\open_apis\approval\v4\external_instances\builders\ExternalInstanceTaskBuilder;

final class ExternalInstance
{
    public static function builder(): ExternalInstanceBuilder
    {
        return ExternalInstanceBuilder::make();
    }

    /** @return class-string<ExternalInstanceTaskBuilder> */
    public static function taskBuilder(): string
    {
        return ExternalInstanceTaskBuilder::class;
    }

    /** @return class-string<ExternalInstanceI18nBuilder> */
    public static function i18nBuilder(): string
    {
        return ExternalInstanceI18nBuilder::class;
    }

    /** @return class-string<ExternalInstanceFormBuilder> */
    public static function formBuilder(): string
    {
        return ExternalInstanceFormBuilder::class;
    }

    /** @return class-string<ExternalInstanceCcBuilder> */
    public static function ccBuilder(): string
    {
        return ExternalInstanceCcBuilder::class;
    }

    /** @return class-string<ExternalInstanceLinkBuilder> */
    public static function linkBuilder(): string
    {
        return ExternalInstanceLinkBuilder::class;
    }
}
