<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\MediaDepartment;

class PagesController extends Controller
{
    public function index()
    {
        $media = MediaDepartment::get();

        return view('dashboard.index', compact('media'));
    }
}