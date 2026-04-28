<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Package extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'monthly_price',
        'yearly_price',
        'cars_count',
        'addons_count',
        'features',
    ];

    protected function casts(): array
    {
        return [
            'monthly_price' => 'decimal:2',
            'yearly_price' => 'decimal:2',
            'features' => 'array',
        ];
    }

    public function allData()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'monthly_price' => $this->monthly_price,
            'yearly_price' => $this->yearly_price,
            'cars_count' => $this->cars_count,
            'addons_count' => $this->addons_count,
            'icon' => $this->getFirstMediaUrl('icon'),
            'features' => $this->features,
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('icon')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp']);
    }
}
