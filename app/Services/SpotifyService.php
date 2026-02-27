<?php

namespace App\Services;

use App\Models\ExternalAccount;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class SpotifyService
{
    public function getValidAccessToken($userId)
    {
        $account = ExternalAccount::where('user_id', $userId)
            ->where('provider', 'spotify')
            ->first();

        if (!$account) {
            return null;
        }

        // Si el token aún es válido
        if ($account->token_expires_at && $account->token_expires_at->isFuture()) {
            return $account->access_token;
        }

        // Token expirado → refrescar
        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $account->refresh_token,
            'client_id' => config('services.spotify.client_id'),
            'client_secret' => config('services.spotify.client_secret'),
        ]);

        $data = $response->json();

        if (!isset($data['access_token'])) {
            return null;
        }

        $account->access_token = $data['access_token'];
        $account->token_expires_at = Carbon::now()->addSeconds($data['expires_in']);

        if (isset($data['refresh_token'])) {
            $account->refresh_token = $data['refresh_token'];
        }

        $account->save();

        return $account->access_token;
    }
}