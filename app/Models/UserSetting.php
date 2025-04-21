<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'default_provider',
        'default_model',
        'default_temperature',
        'default_max_tokens',
        'ui_theme',
        'other_settings',
    ];

    protected $casts = [
        'default_temperature' => 'float',
        'default_max_tokens' => 'integer',
        'other_settings' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
