# feishu/sdk-php — Usage and migration guide

This document describes the new infrastructure after refactor and how to migrate from the removed `feishu\Auth` to the new `feishu\Core\TokenManager` with PSR-16 cache support (MemoryCache for dev, RedisCache for production).

## Breaking change

The old `feishu\Auth` class has been removed. All token-related behavior is now provided by `feishu\Core\TokenManager`.

## Quick start (Memory cache)

```php
use feishu\Core\Config;
use feishu\Core\TokenManager;
use feishu\Core\MemoryCache;
use feishu\Client;

$config = new Config($appId, $appSecret);
$cache = new MemoryCache();
$tm = new TokenManager($config, null, $cache);

$client = new Client($config);
$client->setTokenManager($tm); // inject custom TokenManager

$response = $client->request(\feishu\AnyRequest::get('/im/v1/messages', ['receive_id' => $openId]));
```

## Production (Redis)

Install dependencies:

```bash
composer require predis/predis psr/simple-cache
```

Example:

```php
use Predis\Client as PredisClient;
use feishu\Core\RedisCache;
use feishu\Core\Config;
use feishu\Core\TokenManager;
use feishu\Client;

$predis = new PredisClient(['scheme'=>'tcp','host'=>'127.0.0.1','port'=>6379]);
$cache = new RedisCache($predis, 'feishu:');
$config = new Config($appId, $appSecret);
$tm = new TokenManager($config, null, $cache);

$client = new Client($config);
$client->setTokenManager($tm);
```

### Redis clear() warning
`RedisCache::clear()` uses `KEYS` to remove prefixed entries which can be expensive on large datasets. Prefer using `SCAN` or maintain an index of keys if you need safe bulk deletion in production.

## Migrating from Auth

Replace any code like:

```php
$auth = new \feishu\Auth($appId, $appSecret);
$token = $auth->getTenantAccessToken();
```

With:

```php
$config = new \feishu\Core\Config($appId, $appSecret);
$tm = new \feishu\Core\TokenManager($config, null, $cache);
$token = $tm->getTenantAccessToken();
```

## Client injection

Client still supports `new Client($appId, $appSecret)` or `new Client($config)`. To supply a custom TokenManager (e.g. with Redis caching), call `setTokenManager()` on the `Client` instance as shown above.
