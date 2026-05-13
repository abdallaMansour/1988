<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;

class SiteSettingController extends Controller
{
    public function siteSetting()
    {
        $settings = SiteSetting::select(
            'privacy_policy',
            'terms_and_conditions',
            'about_us',
            'how_to_play',
            'return_replacement_policy',
            'ios_app_link',
            'android_app_link'
        )->first();

        return $this->sendResponse($settings);
    }


}
