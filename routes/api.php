<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\SpotifyController;
use App\Services\SpotifyService;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', function (Request $request) {
        return $request->user();
    });

});

// Redirect a Spotify 
Route::get('/spotify/redirect', [SpotifyController::class, 'redirect']);
// Callback de Spotify (sin auth middleware)
Route::get('/spotify/callback', [SpotifyController::class, 'callback']);

// Otros endpoints
Route::post('/register-with-payment', [AuthController::class, 'registerWithPayment']);
Route::post('/check-email', [AuthController::class, 'checkEmail']);

// Endpoint para refrescar token de Spotify (solo usuarios premium)
Route::middleware(['auth:sanctum', 'premium'])->get('/spotify/token', function (SpotifyService $spotifyService) {
    $token = $spotifyService->getValidAccessToken(auth()->id());
    return response()->json([
        'access_token' => $token
    ]);
});

// Debug
Route::get('/debug-token', function (Request $request) {
    return response()->json([
        'authorization_header' => $request->header('Authorization'),
        'bearer_token' => $request->bearerToken(),
    ]);
});