<?php

declare(strict_types=1);

namespace feishu\open_apis\approval\v4\external_instances\builders;

use InvalidArgumentException;

final class ExternalInstanceLinkBuilder
{
    /**
     * make 构建链接对象
     * @param string $pcLink PC 端链接
     * @param string|null $mobileLink 移动端链接
     * @return array{pc_link:string,mobile_link?:string}
     */
    public static function make(string $pcLink, ?string $mobileLink = null): array
    {
        $pcLink = trim($pcLink);
        $mobileLink = $mobileLink === null ? null : trim($mobileLink);

        if ($pcLink === '' && ($mobileLink === null || $mobileLink === '')) {
            throw new InvalidArgumentException('pcLink 与 mobileLink 至少需要传一个');
        }

        $links = [];
        if ($pcLink !== '') {
            $links['pc_link'] = $pcLink;
        }
        if ($mobileLink !== null && $mobileLink !== '') {
            $links['mobile_link'] = $mobileLink;
        }

        return $links;
    }
}
