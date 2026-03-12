<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\StoryExportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Sanctum protected
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', function (Request $request) {
        return $request->user();
    });
    Route::get('/spotify/status', [SpotifyController::class, 'status']);
});

// Spotify OAuth
Route::get('/spotify/redirect', [SpotifyController::class, 'redirect']);
Route::get('/spotify/callback', [SpotifyController::class, 'callback']);

// Otros endpoints
Route::post('/register-with-payment', [AuthController::class, 'registerWithPayment']);
Route::post('/check-email', [AuthController::class, 'checkEmail']);

// Spotify API (premium)
Route::middleware(['auth:sanctum', 'premium'])->group(function () {
    Route::get('/spotify/token', [SpotifyController::class, 'token']);

    Route::get('/spotify/now-playing', [SpotifyController::class, 'nowPlaying']);
    Route::get('/spotify/playback-state', [SpotifyController::class, 'playbackState']);

    Route::put('/spotify/play', [SpotifyController::class, 'play']);
    Route::put('/spotify/pause', [SpotifyController::class, 'pause']);

    Route::post('/spotify/next', [SpotifyController::class, 'next']);
    Route::post('/spotify/previous', [SpotifyController::class, 'previous']);

    Route::get('/spotify/devices', [SpotifyController::class, 'devices']);
    Route::put('/spotify/transfer', [SpotifyController::class, 'transferPlayback']);

    Route::get('/spotify/albums', [SpotifyController::class, 'albums']);
    Route::get('/spotify/playlists', [SpotifyController::class, 'playlists']);
    Route::get('/spotify/albums/{id}/tracks', [SpotifyController::class, 'albumTracks']);
    Route::get('/spotify/playlists/{id}/items', [SpotifyController::class, 'playlistTracks']);

   

    Route::get('/spotify/queue', [SpotifyController::class, 'queue']);
    Route::post('/spotify/skip', [SpotifyController::class, 'skip']);
    Route::post('/spotify/skip-to', [SpotifyController::class, 'skipTo']);
    Route::post('/story/upload-webm', [StoryExportController::class, 'uploadWebm']);
});
Route::middleware(['auth:sanctum', 'premium'])->put('/spotify/play-from-context', [SpotifyController::class, 'playFromContext']);
// Route::post('/story/convert-mp4', [StoryExportController::class, 'convertToMp4']);
Route::get('/story/{id}/download-mp4', [StoryExportController::class, 'downloadMp4']);

// Debug
// Route::get('/debug-token', function (Request $request) {
//     return response()->json([
//         'authorization_header' => $request->header('Authorization'),
//         'bearer_token' => $request->bearerToken(),
//     ]);
// });