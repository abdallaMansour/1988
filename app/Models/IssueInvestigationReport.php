<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssueInvestigationReport extends Model
{
    protected $fillable = [
        'issue_id',
        'issue_hint_id',
        'title',
        'report',
    ];

    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    public function suspect(): BelongsTo
    {
        return $this->belongsTo(IssueHint::class, 'issue_hint_id');
    }
}
