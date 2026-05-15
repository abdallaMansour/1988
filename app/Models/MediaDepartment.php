<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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

        $this->addMediaCollection('dashboard_banner_video')
            ->singleFile()
            ->acceptsMimeTypes([
                'image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp',
                'video/mp4', 'video/webm',
            ]);
    }

    public function dashboardPromoMedia(): ?Media
    {
        return $this->getFirstMedia('dashboard_banner_video');
    }

    public function dashboardPromoIsVideo(): bool
    {
        $media = $this->dashboardPromoMedia();

        return $media !== null && str_starts_with((string) $media->mime_type, 'video/');
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
