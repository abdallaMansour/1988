<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IssueRound extends Model
{
    protected $fillable = [
        'issue_id',
        'round_number',
    ];

    protected function casts(): array
    {
        return [
            'round_number' => 'integer',
        ];
    }

    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(IssueRoundQuestion::class)->orderBy('sort_order');
    }
}
