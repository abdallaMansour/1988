<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IssueRoundQuestion extends Model
{
    protected $fillable = [
        'issue_round_id',
        'question',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function round(): BelongsTo
    {
        return $this->belongsTo(IssueRound::class, 'issue_round_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(IssueRoundAnswer::class)->orderBy('sort_order');
    }
}
