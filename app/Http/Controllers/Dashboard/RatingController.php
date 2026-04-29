<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::query()->latest('created_at')->paginate(15);

        return view('dashboard.ratings.index', compact('ratings'));
    }

    public function store(Request $request)
    {
        $user = auth('web')->user();

        abort_unless((bool) $user, 403, 'فقط المستخدم يمكنه إضافة تقييم.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'description' => ['required', 'string'],
            'rating' => ['required', 'integer', 'between:1,5'],
        ]);

        Rating::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'description' => $validated['description'],
            'rating' => $validated['rating'],
        ]);

        return back()->with('success', 'تم إرسال التقييم بنجاح.');
    }

    public function destroy(Rating $rating)
    {
        Rating::destroy($rating->id);

        return redirect()->route('dashboard.ratings.index')->with('success', 'تم حذف التقييم بنجاح.');
    }
}
