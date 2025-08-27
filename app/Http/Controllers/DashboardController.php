<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Link;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalLinks   = $user->links()->count();
        $activeLinks  = $user->links()->where('status', 'active')->count();
        $expiredLinks = $user->links()->where('status', 'expired')->count();
        $totalClicks  = $user->links()->sum('click_count');
        $topLinks     = $user->links()->orderByDesc('click_count')->take(10)->get();

        return view('dashboard.index', compact(
            'totalLinks', 'activeLinks', 'expiredLinks', 'totalClicks', 'topLinks'
        ));
    }
}
