<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class IssueEvidence extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'issue_evidences';

    protected $fillable = [
        'issue_id',
        'title',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp']);
    }

    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }
}
