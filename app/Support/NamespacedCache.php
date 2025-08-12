<?php

declare(strict_types=1);

namespace App\Support;

use Closure;
use DateInterval;
use Illuminate\Cache\TaggableStore;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Cache;

class NamespacedCache
{
    /**
     * Remember a value in cache with per-user namespacing and optional tag support.
     * When tags are not supported by the store, a namespace-version key strategy is used for flush.
     */
    public static function rememberUser(
        string $group,
        int $userId,
        string $key,
        DateInterval|int $ttl,
        Closure $callback
    ) {
        $store = Cache::getStore();
        $supportsTags = $store instanceof TaggableStore;

        if ($supportsTags) {
            return Cache::tags(["u:{$userId}", "g:{$group}"])->remember($key, $ttl, $callback);
        }

        $nsKey = self::namespaceKey($group, $userId);
        $version = Cache::get($nsKey, 1);
        $composedKey = self::composeKey($group, (string) $userId, (string) $version, $key);

        return Cache::remember($composedKey, $ttl, $callback);
    }

    /**
     * Flush a per-user cache group. Uses tags when available, otherwise bumps the namespace version.
     */
    public static function flushUserGroup(string $group, int $userId): void
    {
        $store = Cache::getStore();
        $supportsTags = $store instanceof TaggableStore;

        if ($supportsTags) {
            Cache::tags(["u:{$userId}", "g:{$group}"])->flush();
            return;
        }

        $nsKey = self::namespaceKey($group, $userId);
        $current = (int) Cache::get($nsKey, 1);
        Cache::forever($nsKey, $current + 1);
    }

    private static function namespaceKey(string $group, int $userId): string
    {
        return "ns:u:{$userId}:g:{$group}";
    }

    private static function composeKey(string $group, string $userId, string $version, string $key): string
    {
        return "u:{$userId}:g:{$group}:v:{$version}:k:{$key}";
    }
}


