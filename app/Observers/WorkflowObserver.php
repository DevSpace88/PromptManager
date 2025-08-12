<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Workflow;
use App\Support\NamespacedCache;

class WorkflowObserver
{
    public function created(Workflow $workflow): void
    {
        NamespacedCache::flushUserGroup('workflows', (int) $workflow->user_id);
        NamespacedCache::flushUserGroup('dashboard_counts', (int) $workflow->user_id);
    }

    public function updated(Workflow $workflow): void
    {
        NamespacedCache::flushUserGroup('workflows', (int) $workflow->user_id);
    }

    public function deleted(Workflow $workflow): void
    {
        NamespacedCache::flushUserGroup('workflows', (int) $workflow->user_id);
        NamespacedCache::flushUserGroup('dashboard_counts', (int) $workflow->user_id);
    }
}


