<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    try {
        $service = app(\App\Services\AppleMusicService::class);
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $service->generateDeveloperToken(),
        ])->get('https://api.music.apple.com/v1/catalog/us/charts', [
            'types' => 'albums',
            'limit' => 20,
        ]);

        $albums = collect($response->json('results.albums.0.data', []))
            ->map(fn($item) => [
                'id' => $item['id'],
                'title' => $item['attributes']['name'] ?? 'Unknown',
                'image' => str_replace(['{w}', '{h}', '{f}'], ['600', '600', 'jpg'], $item['attributes']['artwork']['url'] ?? ''),
            ])
            ->toArray();
    } catch (\Throwable $e) {
        $albums = [];
    }

    return view('index', compact('albums'));
});

Route::get('/terms', function(){
    return view('terms');
})->name('terms');;

Route::get('/faq', function(){
    return view('faq');
})->name('faq');;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::prefix('apple-music')->group(function () {
    Route::get('/charts', [AppleMusicController::class, 'chartsWebsite']);
});

require __DIR__.'/auth.php';
