<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;   
use App\Models\Link;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class LinkController extends Controller
{
    public function index()
    {
        $links = auth()->user()->links;
        return view('links.index', compact('links'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
            'expires_at'   => 'nullable|date|after:now',
        ]);

        $slug = Str::random(6);

        $link = Link::create([
            'user_id'      => auth()->id(),
            'original_url' => $request->original_url,
            'slug'         => $slug,
            'expires_at'   => $request->expires_at,
        ]);

        // Gerar QRCode (usando "simple-qrcode")
        $qr = QrCode::size(200)->generate(url('/s/' . $slug));


        return view('links.show', compact('link', 'qr'));

    }
}
