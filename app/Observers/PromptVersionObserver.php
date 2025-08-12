<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\PromptVersion;
use App\Support\NamespacedCache;

class PromptVersionObserver
{
    public function created(PromptVersion $version): void
    {
        $userId = (int) $version->prompt->user_id;
        NamespacedCache::flushUserGroup('prompts', $userId);
    }

    public function updated(PromptVersion $version): void
    {
        $userId = (int) $version->prompt->user_id;
        NamespacedCache::flushUserGroup('prompts', $userId);
    }

    public function deleted(PromptVersion $version): void
    {
        $userId = (int) $version->prompt->user_id;
        NamespacedCache::flushUserGroup('prompts', $userId);
    }
}


