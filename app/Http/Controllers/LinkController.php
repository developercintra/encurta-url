<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Models\Link;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LinkController extends Controller
{

    public function index()
    {
        $links = Auth::user()->links()->orderBy('created_at', 'desc')->paginate(10);
        return view('links.index', compact('links'));
    }

    public function show(Link $link)
    {
        Gate::authorize('view', $link);
        return view('links.show', compact('link'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
            'expires_at'   => 'nullable|date|after:now',
        ]);

        do {
            $slug = $this->generateSlug();
        } while (Link::where('slug', $slug)->exists());

        $link = Link::create([
            'user_id'      => auth()->id(),
            'original_url' => $request->original_url,
            'slug'         => $slug,
            'expires_at'   => $request->expires_at,
            'status'       => 'active',
        ]);

        return redirect()->route('links.index')
            ->with('success', 'Link criado com sucesso!');
    }

    public function qrcode(Link $link)
    {
        Gate::authorize('view', $link);
        
        $url = url('/s/' . $link->slug);
        $qrCode = QrCode::format('svg')->size(200)->generate($url);
        
        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    private function generateSlug(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $slug = '';
        for ($i = 0; $i < 6; $i++) {
            $slug .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $slug;
    }
}
