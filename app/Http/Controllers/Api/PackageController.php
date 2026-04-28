<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all()->map(function ($package) {
            return $package->allData();
        });

        return $this->sendResponse($packages);
    }

}
