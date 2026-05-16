<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Rank extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const OPEN_END = 4294967295;

    protected $fillable = [
        'name',
        'solved_issues_from',
        'solved_issues_to',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function isOpenEnded(): bool
    {
        return (int) $this->solved_issues_to >= self::OPEN_END;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp']);
    }
}
