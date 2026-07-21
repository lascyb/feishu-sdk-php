# Breaking changes

This branch contains a breaking refactor to core token handling.

- Removed: `src/Auth.php` (use `feishu\Core\TokenManager` instead)
- Added: `src/Core/Config.php`, `src/Core/TokenManager.php`, `src/Core/MemoryCache.php`, `src/Core/RedisCache.php`
- Updated: `src/Client.php` to depend on Core\TokenManager and provide `setTokenManager()` for injection
- Updated: `composer.json` requires `psr/simple-cache` and `predis/predis`

Migration: see `docs/USAGE.md` for detailed examples.
