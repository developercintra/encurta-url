<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RedirectController extends Controller
{
    public function show($slug, Request $request): RedirectResponse|View
    {
        $link = Link::where('slug', $slug)->firstOrFail();

        if ($link->expires_at && $link->expires_at->isPast()) {
            $link->update(['status' => 'expired']);
            return view('links.expired', compact('link'));
        }

        if ($link->status !== 'active') {
            return view('links.inactive', compact('link'));
        }

        $link->increment('click_count');
        $link->visits()->create([
            'ip_hash' => hash('sha256', $request->ip()),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->away($link->original_url);
    }
}
