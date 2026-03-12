<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;

class AppleMusicService
{
    protected string $baseUrl = 'https://api.music.apple.com/v1';

    public function generateDeveloperToken()
    {
        $privateKey = file_get_contents(storage_path('AuthKey_Q3RABS4B44.p8'));

        $token = JWT::encode(
            [
                'iss' => env('APPLE_TEAM_ID'),
                'iat' => time(),
                'exp' => time() + 15777000
            ],
            $privateKey,
            'ES256',
            env('APPLE_KEY_ID')
        );

        return $token;
    }

    public function search(string $term, string $storefront = 'us', array $types = ['songs', 'albums', 'artists'], int $limit = 10): array
    {
        $token = $this->generateDeveloperToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("{$this->baseUrl}/catalog/{$storefront}/search", [
            'term' => $term,
            'types' => implode(',', $types),
            'limit' => $limit,
        ]);

        if ($response->failed()) {
            throw new \Exception('Apple Music search error: ' . $response->body());
        }

        return $response->json();
    }

    public function getSong(string $id, string $storefront = 'us'): array
    {
        $token = $this->generateDeveloperToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("{$this->baseUrl}/catalog/{$storefront}/songs/{$id}");

        if ($response->failed()) {
            throw new \Exception('Apple Music song error: ' . $response->body());
        }

        return $response->json();
    }

    public function getAlbum(string $id, string $storefront = 'us'): array
    {
        $token = $this->generateDeveloperToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("{$this->baseUrl}/catalog/{$storefront}/albums/{$id}");

        if ($response->failed()) {
            throw new \Exception('Apple Music album error: ' . $response->body());
        }

        return $response->json();
    }

    public function getArtist(string $id, string $storefront = 'us'): array
    {
        $token = $this->generateDeveloperToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("{$this->baseUrl}/catalog/{$storefront}/artists/{$id}");

        if ($response->failed()) {
            throw new \Exception('Apple Music artist error: ' . $response->body());
        }

        return $response->json();
    }
}