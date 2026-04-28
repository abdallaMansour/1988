<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'privacy_policy',
        'terms_and_conditions',
        'about_us',
        'ios_app_link',
        'android_app_link',
    ];

    /**
     * Get the singleton site settings instance.
     */
    public static function get(): self
    {
        $settings = static::first();
        if (!$settings) {
            $settings = static::create([
                'privacy_policy' => '',
                'terms_and_conditions' => '',
                'about_us' => '',
                'ios_app_link' => '',
                'android_app_link' => '',
            ]);
        }
        return $settings;
    }
}
