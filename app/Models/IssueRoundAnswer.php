<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssueRoundAnswer extends Model
{
    protected $fillable = [
        'issue_round_question_id',
        'answer',
        'sort_order',
        'is_correct',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_correct' => 'boolean',
        ];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(IssueRoundQuestion::class, 'issue_round_question_id');
    }
}
