<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\ApiKey;
use App\Support\NamespacedCache;

class ApiKeyObserver
{
    public function created(ApiKey $apiKey): void
    {
        NamespacedCache::flushUserGroup('api_keys', (int) $apiKey->user_id);
        NamespacedCache::flushUserGroup('dashboard_counts', (int) $apiKey->user_id);
    }

    public function updated(ApiKey $apiKey): void
    {
        NamespacedCache::flushUserGroup('api_keys', (int) $apiKey->user_id);
    }

    public function deleted(ApiKey $apiKey): void
    {
        NamespacedCache::flushUserGroup('api_keys', (int) $apiKey->user_id);
        NamespacedCache::flushUserGroup('dashboard_counts', (int) $apiKey->user_id);
    }
}


