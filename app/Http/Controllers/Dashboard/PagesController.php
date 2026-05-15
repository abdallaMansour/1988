<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\MediaDepartment;
use App\Models\Product;
use App\Models\Rank;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(Request $request)
    {
        $media = MediaDepartment::get();

        if ($request->user('web')) {
            $user = $request->user('web');
            $solvedCount = 0;

            $stats = [
                'products_purchased' => $user->purchases()
                    ->where('status', 'paid')
                    ->where('purchasable_type', (new Product)->getMorphClass())
                    ->count(),
                'wins' => 0,
                'losses' => 0,
                'solved_count' => $solvedCount,
            ];

            $currentRank = $this->resolveRank($solvedCount);

            return view('dashboard.index-user', compact('media', 'user', 'stats', 'currentRank'));
        }

        return view('dashboard.index-admin', compact('media'));
    }

    private function resolveRank(int $solvedCount): ?Rank
    {
        return Rank::query()
            ->where('is_active', true)
            ->where('solved_issues_from', '<=', $solvedCount)
            ->where('solved_issues_to', '>=', $solvedCount)
            ->orderBy('solved_issues_from')
            ->first();
    }
}
