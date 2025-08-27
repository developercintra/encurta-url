<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $period = $request->get('period', '30');
        
        $startDate = match($period) {
            '1' => Carbon::today(),
            '7' => Carbon::now()->subDays(7),
            '30' => Carbon::now()->subDays(30),
            default => Carbon::now()->subDays(30)
        };

        $linksQuery = $user->links()->where('created_at', '>=', $startDate);
        
        $totalLinks = $linksQuery->count();
        $activeLinks = $linksQuery->where('status', 'active')->count();
        $expiredLinks = $linksQuery->where('status', 'expired')->count();
        $inactiveLinks = $linksQuery->where('status', 'inactive')->count();
        $totalClicks = $linksQuery->sum('click_count');
        $topLinks = $linksQuery->orderByDesc('click_count')->take(10)->get();

        return view('dashboard', compact(
            'totalLinks', 'activeLinks', 'expiredLinks', 'inactiveLinks', 
            'totalClicks', 'topLinks', 'period'
        ));
    }
}
