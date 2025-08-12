<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\ExecutionLog;
use App\Support\NamespacedCache;

class ExecutionLogObserver
{
    public function created(ExecutionLog $log): void
    {
        NamespacedCache::flushUserGroup('executions', (int) $log->user_id);
    }

    public function updated(ExecutionLog $log): void
    {
        NamespacedCache::flushUserGroup('executions', (int) $log->user_id);
    }
}


