<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;



class RedirectController extends Controller
{
    public function show($slug, Request $request)
    {
        $link = Link::where('slug',$slug)->firstOrFail();

        if ($link->expires_at && now()->greaterThan($link->expires_at)) {
            $link->update(['status'=>'expired']);
            return view('links.expired', compact('link'));
        }

        if ($link->status !== 'active') {
            return view('links.inactive', compact('link'));
        }

        $link->increment('click_count');
        $link->visits()->create([
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->away($link->original_url);
    }
}
