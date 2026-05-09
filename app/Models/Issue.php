<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Issue extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'purchase_price_before_discount',
        'purchase_price_after_discount',
        'is_linked_to_novel',
        'is_active',
        'languages',
        'details',
        'is_related_to_another_issue',
        'related_issue_id',
    ];

    protected function casts(): array
    {
        return [
            'purchase_price_before_discount' => 'decimal:2',
            'purchase_price_after_discount' => 'decimal:2',
            'is_linked_to_novel' => 'boolean',
            'is_active' => 'boolean',
            'languages' => 'array',
            'is_related_to_another_issue' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp']);

        $this->addMediaCollection('story_video')
            ->singleFile()
            ->acceptsMimeTypes(['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime']);

        $this->addMediaCollection('evidence')
            ->acceptsMimeTypes([
                'image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp',
                'video/mp4', 'video/webm', 'video/ogg', 'video/quicktime',
            ]);

        $this->addMediaCollection('ending_video')
            ->singleFile()
            ->acceptsMimeTypes(['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime']);
    }

    public function hints(): HasMany
    {
        return $this->hasMany(IssueHint::class);
    }

    public function relatedIssue(): BelongsTo
    {
        return $this->belongsTo(self::class, 'related_issue_id');
    }

    public function purchases(): MorphMany
    {
        return $this->morphMany(Purchase::class, 'purchasable');
    }
}
