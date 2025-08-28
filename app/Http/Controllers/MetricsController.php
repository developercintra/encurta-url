<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MetricsController extends Controller
{
    public function summary(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', '30');

        $startDate = match ($period) {
            '1' => Carbon::today(),
            '7' => Carbon::now()->subDays(7),
            '30' => Carbon::now()->subDays(30),
            default => Carbon::now()->subDays(30)
        };

        $linksQuery = $user->links()->where('created_at', '>=', $startDate);

        $data = [
            'total_links'   => (clone $linksQuery)->count(),
            'active_links'  => (clone $linksQuery)->where('status', 'active')->count(),
            'expired_links' => (clone $linksQuery)->where('status', 'expired')->count(),
            'inactive_links' => (clone $linksQuery)->where('status', 'inactive')->count(),
            'total_clicks'  => (clone $linksQuery)->sum('click_count'),
            'period'        => $period,
        ];

        return response()->json($data);
    }

    public function top(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', '30');
        $limit = $request->get('limit', 10);

        $startDate = match ($period) {
            '1' => Carbon::today(),
            '7' => Carbon::now()->subDays(7),
            '30' => Carbon::now()->subDays(30),
            default => Carbon::now()->subDays(30)
        };

        $topLinks = $user->links()
            ->where('created_at', '>=', $startDate)
            ->orderByDesc('click_count')
            ->take($limit)
            ->get(['id', 'original_url', 'slug', 'click_count', 'status', 'created_at']);

        return response()->json([
            'links' => $topLinks,
            'period' => $period
        ]);
    }
}
