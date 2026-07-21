<?php

namespace feishu;

/**
 * Removed: Auth has been removed in favor of feishu\Core\TokenManager.
 * This file intentionally throws to indicate the breaking change.
 */

throw new \Error('feishu\\Auth has been removed. Use feishu\\Core\\TokenManager with a PSR-16 cache (e.g. feishu\\Core\\RedisCache) instead.');
