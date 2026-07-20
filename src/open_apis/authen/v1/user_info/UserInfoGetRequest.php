<?php

namespace feishu\open_apis\authen\v1\user_info;

use feishu\Contract\RequestInterface;

/**
 * UserInfoGetRequest 获取用户信息（user_access_token）
 * 文档：https://open.feishu.cn/document/server-docs/authentication-management/login-state-management/get
 * API：GET /open-apis/authen/v1/user_info
 */
class UserInfoGetRequest implements RequestInterface
{
    public function getMethod()
    {
        return 'GET';
    }

    public function getPath()
    {
        return '/authen/v1/user_info';
    }

    public function getQuery()
    {
        return [];
    }

    public function getBody()
    {
        return null;
    }

    public function getHeaders()
    {
        return [];
    }
}
