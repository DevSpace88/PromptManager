<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Prompt;
use App\Support\NamespacedCache;

class PromptObserver
{
    public function created(Prompt $prompt): void
    {
        NamespacedCache::flushUserGroup('prompts', (int) $prompt->user_id);
        NamespacedCache::flushUserGroup('dashboard_counts', (int) $prompt->user_id);
    }

    public function updated(Prompt $prompt): void
    {
        NamespacedCache::flushUserGroup('prompts', (int) $prompt->user_id);
        NamespacedCache::flushUserGroup('dashboard_counts', (int) $prompt->user_id);
    }

    public function deleted(Prompt $prompt): void
    {
        NamespacedCache::flushUserGroup('prompts', (int) $prompt->user_id);
        NamespacedCache::flushUserGroup('dashboard_counts', (int) $prompt->user_id);
    }
}


