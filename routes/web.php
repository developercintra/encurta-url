<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MetricsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/links', [LinkController::class, 'index'])->name('links.index');
    Route::post('/links', [LinkController::class, 'store'])->name('links.store')->middleware('throttle:30,1');
    Route::get('/links/{link}', [LinkController::class, 'show'])->name('links.show');
    Route::get('/links/{link}/qr', [LinkController::class, 'qrcode'])->name('links.qrcode');

    Route::get('/metrics/summary', [MetricsController::class, 'summary'])->name('metrics.summary');
    Route::get('/metrics/top', [MetricsController::class, 'top'])->name('metrics.top');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/s/{slug}', [RedirectController::class, 'show'])->name('redirect');

require __DIR__.'/auth.php';
