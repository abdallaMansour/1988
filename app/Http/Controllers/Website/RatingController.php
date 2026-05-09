<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::query()
            ->latest('created_at')
            ->paginate(12);

        return view('website.pages.ratings', compact('ratings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'description' => ['required', 'string'],
            'rating' => ['required', 'integer', 'between:1,5'],
        ]);

        Rating::create([
            'user_id' => auth('web')->id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'description' => $validated['description'],
            'rating' => $validated['rating'],
        ]);

        return redirect()->route('website.ratings')->with('success', 'تم إرسال التقييم بنجاح.');
    }
}
