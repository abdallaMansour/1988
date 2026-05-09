<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Rating;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::query()->latest('created_at')->paginate(15);

        return view('dashboard.ratings.index', compact('ratings'));
    }

    public function destroy(Rating $rating)
    {
        Rating::destroy($rating->id);

        return redirect()->route('dashboard.ratings.index')->with('success', 'تم حذف التقييم بنجاح.');
    }
}
