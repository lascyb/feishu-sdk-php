<?php

declare(strict_types=1);

namespace feishu\open_apis\approval\v4\external_instances\builders;

use InvalidArgumentException;

final class ExternalInstanceI18nBuilder
{
    public const PREFIX = '@i18n@';

    public const TITLE = 'title';
    public const USER_NAME = 'user_name';
    public const DEPARTMENT_NAME = 'department_name';

    /**
     * key 生成 i18n Key（自动补 @i18n@ 前缀）
     * @param string $key 字段 key
     * @return string
     */
    public static function key(string $key): string
    {
        $key = trim($key);
        if ($key === '') {
            throw new InvalidArgumentException('i18n key 不能为空');
        }

        return str_starts_with($key, self::PREFIX) ? $key : self::PREFIX . $key;
    }

    /**
     * zhCn 构建中文默认国际化资源
     * @param array<string, string|int|float|null> $texts key => 文案
     * @return array<int, array{locale:string,is_default:bool,texts:array<int, array{key:string,value:string}>}>
     */
    public static function zhCn(array $texts): array
    {
        $normalized = [];
        foreach ($texts as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $normalized[] = [
                'key' => self::key((string) $key),
                'value' => (string) $value,
            ];
        }

        if ($normalized === []) {
            throw new InvalidArgumentException('i18n texts 不能为空');
        }

        return [
            [
                'locale' => 'zh-CN',
                'is_default' => true,
                'texts' => $normalized,
            ],
        ];
    }

    /**
     * assertContainsKeys 校验 i18n 资源是否包含指定 key
     * @param array<int, array<string, mixed>> $i18nResources 国际化资源
     * @param array<int, string> $keys i18n Key 列表
     * @return void
     */
    public static function assertContainsKeys(array $i18nResources, array $keys): void
    {
        $availableKeys = [];
        foreach ($i18nResources as $resource) {
            $texts = $resource['texts'] ?? [];
            if (!is_array($texts)) {
                continue;
            }
            foreach ($texts as $textItem) {
                if (!is_array($textItem) || !isset($textItem['key'])) {
                    continue;
                }
                $availableKeys[(string) $textItem['key']] = true;
            }
        }

        foreach ($keys as $key) {
            $normalizedKey = self::key($key);
            if (!isset($availableKeys[$normalizedKey])) {
                throw new InvalidArgumentException('i18n_resources 缺少 key: ' . $normalizedKey);
            }
        }
    }
}
