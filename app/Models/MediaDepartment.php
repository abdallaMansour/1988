<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MediaDepartment extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'media_department';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('login_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp']);

        $this->addMediaCollection('register_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp']);

        $this->addMediaCollection('dashboard_banner')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp']);
    }

    /**
     * Get the singleton media department instance.
     */
    public static function get(): self
    {
        $instance = static::first();
        if (!$instance) {
            $instance = static::create([]);
        }
        return $instance;
    }
}
