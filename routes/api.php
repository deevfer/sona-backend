<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\StoryExportController;
use App\Http\Controllers\AppleMusicController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register-with-payment', [AuthController::class, 'registerWithPayment']);
Route::post('/check-email', [AuthController::class, 'checkEmail']);


// Sanctum protected
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/heartbeat', function (Request $request) {
        \Log::info('Heartbeat received', ['user' => $request->user()->id]);
        $request->user()->currentAccessToken()->update(['last_used_at' => now()]);
        return response()->json(['ok' => true]);
    });

    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    // Provider status
    Route::get('/spotify/status', [SpotifyController::class, 'status']);
    Route::get('/apple-music/status', [AppleMusicController::class, 'status']);
});


// Spotify OAuth
Route::get('/spotify/redirect', [SpotifyController::class, 'redirect']);
Route::get('/spotify/callback', [SpotifyController::class, 'callback']);

Route::get('/apple-music/landing-artworks', [AppleMusicController::class, 'landingArtworks']);

// Spotify + Apple Music API (premium)
Route::middleware(['auth:sanctum', 'premium'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Spotify
    |--------------------------------------------------------------------------
    */

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

    Route::put('/spotify/play-from-context', [SpotifyController::class, 'playFromContext']);


    /*
    |--------------------------------------------------------------------------
    | Story export
    |--------------------------------------------------------------------------
    */

    Route::post('/story/upload-webm', [StoryExportController::class, 'uploadWebm']);


    /*
    |--------------------------------------------------------------------------
    | Apple Music
    |--------------------------------------------------------------------------
    */

    // Developer token
    Route::get('/apple-music/token', [AppleMusicController::class, 'token']);

    // Guardar Music User Token
    Route::post('/apple-music/connect', [AppleMusicController::class, 'connect']);


    /*
    |--------------------------------------------------------------------------
    | Apple Music - Catálogo (Apple global catalog)
    |--------------------------------------------------------------------------
    */

    Route::prefix('apple-music')->group(function () {

        Route::get('/search', [AppleMusicController::class, 'search']);

        Route::get('/songs/{id}', [AppleMusicController::class, 'song']);

        Route::get('/albums/{id}', [AppleMusicController::class, 'album']);
        Route::get('/albums/{id}/tracks', [AppleMusicController::class, 'albumTracks']);

        Route::get('/artists/{id}', [AppleMusicController::class, 'artist']);

        Route::get('/playlists/{id}', [AppleMusicController::class, 'playlist']);
        Route::get('/playlists/{id}/tracks', [AppleMusicController::class, 'playlistTracks']);

        Route::get('/charts', [AppleMusicController::class, 'charts']);
    });


    /*
    |--------------------------------------------------------------------------
    | Apple Music - Biblioteca del usuario
    |--------------------------------------------------------------------------
    | Estos endpoints usan:
    | Authorization: Bearer <developer token>
    | Music-User-Token: <music_user_token>
    |--------------------------------------------------------------------------
    */

    Route::prefix('apple-music/me/library')->group(function () {

        Route::get('/albums', [AppleMusicController::class, 'libraryAlbums']);
        Route::get('/playlists', [AppleMusicController::class, 'libraryPlaylists']);

        Route::get('/albums/{id}', [AppleMusicController::class, 'libraryAlbum']);
        Route::get('/playlists/{id}', [AppleMusicController::class, 'libraryPlaylist']);
    });
});


// Story download
Route::get('/story/{id}/download-mp4', [StoryExportController::class, 'downloadMp4']);