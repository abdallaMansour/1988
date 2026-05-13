<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SiteSetting extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'site_settings';

    protected $fillable = [
        'privacy_policy',
        'terms_and_conditions',
        'about_us',
        'how_to_play',
        'return_replacement_policy',
        'novel_title',
        'novel_description',
        'ios_app_link',
        'android_app_link',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('novel_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp']);
    }

    /**
     * Get the singleton site settings instance.
     */
    public static function singleton(): self
    {
        $settings = static::query()->first();
        if (!$settings) {
            $settings = static::create([
                'privacy_policy' => '',
                'terms_and_conditions' => '',
                'about_us' => '',
                'how_to_play' => '',
                'return_replacement_policy' => '',
                'novel_title' => '',
                'novel_description' => '',
                'ios_app_link' => '',
                'android_app_link' => '',
            ]);
        }
        return $settings;
    }
}
