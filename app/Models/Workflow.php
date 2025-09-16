<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
//use Spatie\Activitylog\Traits\LogsActivity;
//use Spatie\Activitylog\LogOptions;

class Workflow extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'nodes',
        'edges',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'nodes' => 'array',
        'edges' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $workflow) {
            // Ensure arrays are arrays; avoid null casting surprises
            $workflow->nodes = is_array($workflow->nodes) ? array_values($workflow->nodes) : [];
            $workflow->edges = is_array($workflow->edges) ? array_values($workflow->edges) : [];
            if ($workflow->settings === null) {
                $workflow->settings = [];
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'description', 'nodes', 'edges', 'settings', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function executionLogs(): HasMany
    {
        return $this->hasMany(ExecutionLog::class);
    }
}
