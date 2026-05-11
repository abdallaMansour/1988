<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssueWitnessTestimony extends Model
{
    protected $fillable = [
        'issue_id',
        'issue_witness_id',
        'title',
        'report',
    ];

    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    public function witness(): BelongsTo
    {
        return $this->belongsTo(IssueWitness::class, 'issue_witness_id');
    }
}
