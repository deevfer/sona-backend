<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\ExternalAccount;

class SpotifyController extends Controller
{
    // 1. REDIRECT A SPOTIFY
    public function redirect(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return response()->json(['error' => 'Token faltante'], 400);
        }

        $query = http_build_query([
            'client_id' => config('services.spotify.client_id'),
            'response_type' => 'code',
            'redirect_uri' => config('services.spotify.redirect'),
            'scope' => 'user-library-read user-read-private',
            'state' => $token // 🔥 AQUÍ VIAJA EL TOKEN
        ]);

        return response()->json([
            'url' => 'https://accounts.spotify.com/authorize?' . $query
        ]);
    }

    // 2. CALLBACK DE SPOTIFY
    public function callback(Request $request)
    {
        $code = $request->query('code');
        $token = $request->query('state'); // 🔥 Spotify devuelve el token aquí

        if (!$code || !$token) {
            return response()->json(['error' => 'Parámetros faltantes'], 400);
        }

        // Buscar usuario por token Sanctum
        $personalToken = PersonalAccessToken::findToken($token);

        if (!$personalToken) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        $user = $personalToken->tokenable;

        // Obtener tokens de Spotify
        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => config('services.spotify.redirect'),
            'client_id' => config('services.spotify.client_id'),
            'client_secret' => config('services.spotify.client_secret'),
        ]);

        $data = $response->json();

        // Obtener info del usuario Spotify
        $spotifyUser = Http::withToken($data['access_token'])
            ->get('https://api.spotify.com/v1/me')
            ->json();

        $scopesArray = isset($data['scope'])
            ? explode(' ', $data['scope'])
            : [];

        // Guardar en DB
        ExternalAccount::updateOrCreate(
            [
                'user_id' => $user->id,
                'provider' => 'spotify',
            ],
            [
                'provider_user_id' => $spotifyUser['id'] ?? null,
                'access_token' => $data['access_token'] ?? null,
                'refresh_token' => $data['refresh_token'] ?? null,
                'token_expires_at' => isset($data['expires_in'])
                    ? now()->addSeconds($data['expires_in'])
                    : null,
                'scopes' => json_encode($scopesArray),
            ]
        );

        // Regresar al frontend
        return redirect('http://localhost:5173/sona');
    }
}