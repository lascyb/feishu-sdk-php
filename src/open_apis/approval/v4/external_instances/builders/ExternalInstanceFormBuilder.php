<?php

declare(strict_types=1);

namespace feishu\open_apis\approval\v4\external_instances\builders;

use InvalidArgumentException;

final class ExternalInstanceFormBuilder
{
    public const MAX_ITEMS = 3;

    /**
     * item 构建表单项
     * @param string $nameKey 字段名 i18n Key
     * @param string $valueKey 字段值 i18n Key
     * @return array{name:string,value:string}
     */
    public static function item(string $nameKey, string $valueKey): array
    {
        return [
            'name' => ExternalInstanceI18nBuilder::key($nameKey),
            'value' => ExternalInstanceI18nBuilder::key($valueKey),
        ];
    }

    /**
     * make 构建表单列表
     * @param array<int, array{name:string,value:string}> $items 表单项列表
     * @return array<int, array{name:string,value:string}>
     */
    public static function make(array $items): array
    {
        if (count($items) > self::MAX_ITEMS) {
            throw new InvalidArgumentException('form 最多展示 3 项');
        }

        return array_values($items);
    }
}
