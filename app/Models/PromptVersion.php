<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromptVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'prompt_id',
        'content',
        'variables',
        'version',
        'is_current',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_current' => 'boolean',
    ];

    public function prompt(): BelongsTo
    {
        return $this->belongsTo(Prompt::class);
    }

    // Extract variables from content {{variable}}
    public function extractVariablesFromContent()
    {
        preg_match_all('/\{\{(.*?)\}\}/', $this->content, $matches);
        $this->variables = array_unique($matches[1]);
        return $this->variables;
    }
}
