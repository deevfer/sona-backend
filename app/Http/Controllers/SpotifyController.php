<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\ExternalAccount;
use App\Services\SpotifyService;

class SpotifyController extends Controller
{
    // =========================
    // Helpers state base64-url
    // =========================
    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $value): string
    {
        $value = strtr($value, '-_', '+/');
        $pad = strlen($value) % 4;
        if ($pad) $value .= str_repeat('=', 4 - $pad);
        return base64_decode($value);
    }

    // ✅ cap duro para que Spotify no te "congele" horas
    private function capRetryAfter($value, int $min = 1, int $max = 30): int
    {
        $n = (int) ($value ?? 0);
        if ($n < $min) return $min;
        if ($n > $max) return $max;
        return $n;
    }

    // =========================
    // 1) REDIRECT A SPOTIFY
    // =========================
    public function redirect(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return response()->json(['error' => 'Token faltante'], 400);
        }

        $scopes = implode(' ', [
            'user-read-private',
            'user-read-email',
            'user-read-currently-playing',
            'user-read-playback-state',
            'user-modify-playback-state',
            'user-library-read',
            'playlist-modify-public',
            'playlist-modify-private',
            'playlist-read-private',
            'playlist-read-collaborative',
        ]);

        $state = $this->base64UrlEncode($token);

        $query = http_build_query([
            'client_id' => config('services.spotify.client_id'),
            'response_type' => 'code',
            'redirect_uri' => config('services.spotify.redirect'),
            'scope' => $scopes,
            'state' => $state,
            'show_dialog' => 'true',
        ]);

        return response()->json([
            'url' => 'https://accounts.spotify.com/authorize?' . $query
        ]);
    }

    // =========================
    // 2) CALLBACK
    // =========================
    public function callback(Request $request)
    {
        $code  = $request->query('code');
        $state = $request->query('state');
        $error = $request->query('error');

        if ($error) {
            return response()->json(['error' => 'spotify_auth_error', 'message' => $error], 401);
        }

        if (!$code || !$state) {
            return response()->json(['error' => 'Parámetros faltantes'], 400);
        }

        $token = $this->base64UrlDecode($state);

        $personalToken = PersonalAccessToken::findToken($token);
        if (!$personalToken) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        $user = $personalToken->tokenable;

        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => config('services.spotify.redirect'),
            'client_id' => config('services.spotify.client_id'),
            'client_secret' => config('services.spotify.client_secret'),
        ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'spotify_token_error',
                'status' => $response->status(),
                'body' => $response->json(),
            ], 500);
        }

        $data = $response->json();

        $spotifyMe = Http::withToken($data['access_token'])
            ->get('https://api.spotify.com/v1/me');

        if (!$spotifyMe->successful()) {
            return response()->json([
                'error' => 'spotify_me_error',
                'status' => $spotifyMe->status(),
                'body' => $spotifyMe->json()
            ], 500);
        }

        $spotifyUser = $spotifyMe->json();
        $scopesArray = isset($data['scope']) ? explode(' ', $data['scope']) : [];

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
                    ? now()->addSeconds((int) $data['expires_in'])
                    : null,
                'scopes' => $scopesArray,
            ]
        );

        return redirect('http://localhost:5181/sona');
    }

    // =========================
    // Premium endpoints
    // =========================

    public function token(SpotifyService $spotifyService)
    {
        $token = $spotifyService->getValidAccessToken(auth()->id());
        return response()->json(['access_token' => $token]);
    }

    public function nowPlaying(SpotifyService $spotifyService)
    {
        $userId = auth()->id();

        $cacheKey = "spotify:nowplaying:{$userId}";
        $lastGoodKey = "spotify:last_good_nowplaying:{$userId}";

        $cached = Cache::get($cacheKey);
        if ($cached) {
            return response()->json($cached, 200);
        }

        $accessToken = $spotifyService->getValidAccessToken($userId);

        $res = Http::withToken($accessToken)
            ->connectTimeout(5)
            ->timeout(20)
            ->get('https://api.spotify.com/v1/me/player/currently-playing');

        if ($res->status() === 204) {
            $payload = [
                'is_playing' => false,
                'track' => null,
                'progress_ms' => null,
            ];

            Cache::put($cacheKey, $payload, 2);
            Cache::put($lastGoodKey, $payload, 600);
            return response()->json($payload, 200);
        }

        if ($res->status() === 429) {
            $retryAfter = (int) ($res->header('Retry-After') ?? 2);
            $last = Cache::get($lastGoodKey);

            return response()
                ->json([
                    'error' => 'rate_limited',
                    'retry_after' => $retryAfter,
                    'last' => $last,
                ], 429)
                ->header('Retry-After', $retryAfter);
        }

        if (!$res->ok()) {
            return response()->json([
                'error' => 'spotify_error',
                'status' => $res->status(),
                'body' => $res->json(),
            ], $res->status());
        }

        $data = $res->json();

        $payload = [
            'is_playing' => $data['is_playing'] ?? false,
            'progress_ms' => $data['progress_ms'] ?? null,
            'track' => [
                'id' => $data['item']['id'] ?? null,
                'name' => $data['item']['name'] ?? null,
                'artists' => collect($data['item']['artists'] ?? [])->pluck('name')->values(),
                'album' => $data['item']['album']['name'] ?? null,
                'image' => $data['item']['album']['images'][0]['url'] ?? null,
                'duration_ms' => $data['item']['duration_ms'] ?? null,
            ],
        ];

        Cache::put($cacheKey, $payload, 2);
        Cache::put($lastGoodKey, $payload, 600);

        return response()->json($payload, 200);
    }

    public function playbackState(SpotifyService $spotifyService)
    {
        $accessToken = $spotifyService->getValidAccessToken(auth()->id());

        $res = Http::withToken($accessToken)
            ->connectTimeout(5)
            ->timeout(20)
            ->get('https://api.spotify.com/v1/me/player');

        if ($res->status() === 204) {
            return response()->json(['is_playing' => false, 'device' => null], 200);
        }

        if ($res->status() === 429) {
            $retryAfter = $this->capRetryAfter($res->header('Retry-After') ?? 1, 1, 30);

            return response()
                ->json(['error' => 'rate_limited', 'retry_after' => $retryAfter], 429)
                ->header('Retry-After', $retryAfter);
        }

        if (!$res->ok()) {
            return response()->json([
                'error' => 'spotify_error',
                'status' => $res->status(),
                'body' => $res->json(),
            ], $res->status());
        }

        $data = $res->json();

        return response()->json([
            'is_playing' => $data['is_playing'] ?? false,
            'device' => $data['device'] ?? null,
        ]);
    }

    public function devices(SpotifyService $spotifyService)
    {
        $accessToken = $spotifyService->getValidAccessToken(auth()->id());

        $res = Http::withToken($accessToken)
            ->connectTimeout(5)
            ->timeout(20)
            ->get('https://api.spotify.com/v1/me/player/devices');

        if ($res->status() === 429) {
            $retryAfter = $this->capRetryAfter($res->header('Retry-After') ?? 1, 1, 30);

            return response()
                ->json(['error' => 'rate_limited', 'retry_after' => $retryAfter], 429)
                ->header('Retry-After', $retryAfter);
        }

        if (!$res->ok()) {
            return response()->json([
                'error' => 'spotify_error',
                'status' => $res->status(),
                'body' => $res->json(),
            ], $res->status());
        }

        return response()->json($res->json(), 200);
    }

    public function transferPlayback(Request $request, SpotifyService $spotifyService)
    {
        $accessToken = $spotifyService->getValidAccessToken(auth()->id());

        $deviceId = $request->input('device_id');
        if (!$deviceId) {
            return response()->json(['error' => 'device_id_required'], 422);
        }

        $res = Http::withToken($accessToken)
            ->put('https://api.spotify.com/v1/me/player', [
                'device_ids' => [$deviceId],
                'play' => false,
            ]);

        if ($res->status() === 429) {
            $retryAfter = $this->capRetryAfter($res->header('Retry-After') ?? 1, 1, 30);

            return response()
                ->json(['error' => 'rate_limited', 'retry_after' => $retryAfter], 429)
                ->header('Retry-After', $retryAfter);
        }

        if (!in_array($res->status(), [200, 202, 204])) {
            return response()->json([
                'error' => 'spotify_error',
                'status' => $res->status(),
                'body' => $res->json(),
            ], $res->status());
        }

        return response()->json(['ok' => true], 200);
    }

    public function play(Request $request, SpotifyService $spotifyService)
    {
        $accessToken = $spotifyService->getValidAccessToken(auth()->id());

        $res = Http::withToken($accessToken)
            ->send('PUT', 'https://api.spotify.com/v1/me/player/play');

        if (in_array($res->status(), [200, 202, 204])) {
            return response()->json(['ok' => true], 200);
        }

        if ($res->status() === 429) {
            $retryAfter = $this->capRetryAfter($res->header('Retry-After') ?? 1, 1, 30);

            return response()
                ->json(['error' => 'rate_limited', 'retry_after' => $retryAfter], 429)
                ->header('Retry-After', $retryAfter);
        }

        if ($res->status() === 400) {
            $devicesRes = Http::withToken($accessToken)
                ->connectTimeout(5)
                ->timeout(20)
                ->get('https://api.spotify.com/v1/me/player/devices');

            if ($devicesRes->status() === 429) {
                $retryAfter = $this->capRetryAfter($devicesRes->header('Retry-After') ?? 1, 1, 30);

                return response()
                    ->json(['error' => 'rate_limited', 'retry_after' => $retryAfter], 429)
                    ->header('Retry-After', $retryAfter);
            }

            if (!$devicesRes->ok()) {
                return response()->json([
                    'error' => 'spotify_error',
                    'status' => $devicesRes->status(),
                    'body' => $devicesRes->json(),
                ], $devicesRes->status());
            }

            $devices = $devicesRes->json()['devices'] ?? [];

            $chosen = collect($devices)->firstWhere('is_active', true)
                ?? (count($devices) ? $devices[0] : null);

            if (!$chosen || empty($chosen['id'])) {
                return response()->json([
                    'error' => 'no_active_device',
                    'message' => 'No hay ningún dispositivo disponible. Abre Spotify (teléfono/PC) y reproduce algo una vez.',
                ], 409);
            }

            Http::withToken($accessToken)->put('https://api.spotify.com/v1/me/player', [
                'device_ids' => [$chosen['id']],
                'play' => false,
            ]);

            $res2 = Http::withToken($accessToken)
                ->put("https://api.spotify.com/v1/me/player/play?device_id={$chosen['id']}", []);

            if (in_array($res2->status(), [200, 202, 204])) {
                return response()->json(['ok' => true, 'device' => $chosen], 200);
            }

            return response()->json([
                'error' => 'spotify_error',
                'status' => $res2->status(),
                'body' => $res2->json(),
            ], $res2->status());
        }

        return response()->json([
            'error' => 'spotify_error',
            'status' => $res->status(),
            'body' => $res->json(),
        ], $res->status());
    }

    public function pause(Request $request, SpotifyService $spotifyService)
    {
        $accessToken = $spotifyService->getValidAccessToken(auth()->id());

        $res = Http::withToken($accessToken)
            ->send('PUT', 'https://api.spotify.com/v1/me/player/pause');

        if (in_array($res->status(), [200, 202, 204])) {
            return response()->json(['ok' => true], 200);
        }

        if ($res->status() === 429) {
            $retryAfter = $this->capRetryAfter($res->header('Retry-After') ?? 1, 1, 30);

            return response()
                ->json(['error' => 'rate_limited', 'retry_after' => $retryAfter], 429)
                ->header('Retry-After', $retryAfter);
        }

        return response()->json([
            'error' => 'spotify_error',
            'status' => $res->status(),
            'body' => $res->json(),
        ], $res->status());
    }

    public function next(Request $request, SpotifyService $spotifyService)
    {
        $accessToken = $spotifyService->getValidAccessToken(auth()->id());

        $res = Http::withToken($accessToken)
            ->send('POST', 'https://api.spotify.com/v1/me/player/next');

        if (in_array($res->status(), [200, 202, 204])) {
            return response()->json(['ok' => true], 200);
        }

        if ($res->status() === 429) {
            $retryAfter = $this->capRetryAfter($res->header('Retry-After') ?? 1, 1, 30);

            return response()
                ->json(['error' => 'rate_limited', 'retry_after' => $retryAfter], 429)
                ->header('Retry-After', $retryAfter);
        }

        return response()->json([
            'error' => 'spotify_error',
            'status' => $res->status(),
            'body' => $res->json(),
        ], $res->status());
    }

    public function previous(Request $request, SpotifyService $spotifyService)
    {
        $accessToken = $spotifyService->getValidAccessToken(auth()->id());

        $res = Http::withToken($accessToken)
            ->send('POST', 'https://api.spotify.com/v1/me/player/previous');

        if (in_array($res->status(), [200, 202, 204])) {
            return response()->json(['ok' => true], 200);
        }

        if ($res->status() === 429) {
            $retryAfter = $this->capRetryAfter($res->header('Retry-After') ?? 1, 1, 30);

            return response()
                ->json(['error' => 'rate_limited', 'retry_after' => $retryAfter], 429)
                ->header('Retry-After', $retryAfter);
        }

        return response()->json([
            'error' => 'spotify_error',
            'status' => $res->status(),
            'body' => $res->json(),
        ], $res->status());
    }

    public function playlists(Request $request)
    {
        $token = app(\App\Services\SpotifyService::class)
            ->getValidAccessToken($request->user()->id);

        $meRes = Http::withToken($token)
            ->get('https://api.spotify.com/v1/me');
        $spotifyUserId = $meRes->json()['id'] ?? null;

        $response = Http::withToken($token)
            ->get('https://api.spotify.com/v1/me/playlists', [
                'limit' => 50
            ]);

        $data = $response->json();

        if ($spotifyUserId && isset($data['items'])) {
            $data['items'] = array_values(
                array_filter($data['items'], function ($playlist) use ($spotifyUserId) {
                    $ownerId = $playlist['owner']['id'] ?? '';
                    return $ownerId === $spotifyUserId || $ownerId === 'spotify';
                })
            );
        }

        return response()->json($data);
    }

    public function albums(Request $request)
    {
        $token = app(\App\Services\SpotifyService::class)
            ->getValidAccessToken($request->user()->id);

        $response = Http::withToken($token)
            ->get('https://api.spotify.com/v1/me/albums', [
                'limit' => 20
            ]);

        return response()->json($response->json());
    }

    public function albumTracks(Request $request, $id)
    {
        $token = app(\App\Services\SpotifyService::class)
            ->getValidAccessToken($request->user()->id);

        $response = Http::withToken($token)
            ->get("https://api.spotify.com/v1/albums/{$id}/tracks", [
                'limit' => 50
            ]);

        return response()->json($response->json(), $response->status());
    }

    public function playlistTracks(Request $request, $id)
    {
        $token = app(\App\Services\SpotifyService::class)
            ->getValidAccessToken($request->user()->id);

        $response = Http::withToken($token)
            ->get("https://api.spotify.com/v1/playlists/{$id}/items", [
                'limit' => 50
            ]);

        if (!$response->ok()) {
            return response()->json([
                'error' => 'spotify_error',
                'status' => $response->status(),
                'body' => $response->json(),
                'raw' => $response->body(),
                'www_authenticate' => $response->header('WWW-Authenticate'),
            ], $response->status());
        }

        return response()->json($response->json(), 200);
    }

    public function queue(Request $request)
    {
        $token = app(\App\Services\SpotifyService::class)
            ->getValidAccessToken($request->user()->id);

        $response = Http::withToken($token)
            ->get('https://api.spotify.com/v1/me/player/queue');

        return response()->json($response->json());
    }

    public function playFromContext(Request $request)
    {
        $token = app(\App\Services\SpotifyService::class)
            ->getValidAccessToken($request->user()->id);

        $payload = ['position_ms' => 0];

        if ($request->has('uris')) {
            $payload['uris'] = $request->input('uris');
        } elseif ($request->has('context_uri')) {
            $payload['context_uri'] = $request->input('context_uri');
            $position = (int) $request->input('position', 0);
            $payload['offset'] = ['position' => max(0, $position)];
        } else {
            return response()->json([
                'error' => 'validation_error',
                'message' => 'context_uri or uris is required'
            ], 422);
        }

        $res = Http::withToken($token)
            ->put('https://api.spotify.com/v1/me/player/play', $payload);

        if (in_array($res->status(), [200, 202, 204])) {
            return response()->json(['ok' => true], 200);
        }

        return response()->json([
            'error' => 'spotify_error',
            'status' => $res->status(),
            'body' => $res->json(),
        ], $res->status());
    }

    public function skip(Request $request)
    {
        $token = app(\App\Services\SpotifyService::class)
            ->getValidAccessToken($request->user()->id);

        $res = Http::withToken($token)
            ->post('https://api.spotify.com/v1/me/player/next');

        if (in_array($res->status(), [200, 202, 204])) {
            return response()->json(['ok' => true], 200);
        }

        return response()->json([
            'error' => 'spotify_error',
            'status' => $res->status(),
            'body' => $res->json(),
        ], $res->status());
    }

    public function skipTo(Request $request)
    {
        $token = app(\App\Services\SpotifyService::class)
            ->getValidAccessToken($request->user()->id);

        $times = (int) $request->input('times', 1);
        $times = max(1, min($times, 50));

        $playerRes = Http::withToken($token)
            ->get('https://api.spotify.com/v1/me/player');
        $currentVolume = $playerRes->json()['device']['volume_percent'] ?? 50;

        Http::withToken($token)
            ->put('https://api.spotify.com/v1/me/player/volume?volume_percent=0');

        for ($i = 0; $i < $times; $i++) {
            Http::withToken($token)
                ->post('https://api.spotify.com/v1/me/player/next');
        }

        usleep(300000);

        Http::withToken($token)
            ->put('https://api.spotify.com/v1/me/player/volume?volume_percent=' . $currentVolume);

        return response()->json(['ok' => true]);
    }

    public function status(Request $request)
    {
        $user = $request->user();

        $account = ExternalAccount::where('user_id', $user->id)
            ->where('provider', 'spotify')
            ->first();

        if (!$account) {
            return response()->json([
                'connected' => false,
                'provider' => 'spotify',
                'reason' => 'no_account',
            ]);
        }

        if (!$account->access_token) {
            return response()->json([
                'connected' => false,
                'provider' => 'spotify',
                'reason' => 'no_access_token',
            ]);
        }

        try {
            $token = app(SpotifyService::class)->getValidAccessToken($user->id);

            if (!$token) {
                return response()->json([
                    'connected' => false,
                    'provider' => 'spotify',
                    'reason' => 'no_valid_token',
                ]);
            }

            $me = Http::withToken($token)
                ->acceptJson()
                ->get('https://api.spotify.com/v1/me');

            if ($me->successful()) {
                $spotifyUser = $me->json();

                return response()->json([
                    'connected' => true,
                    'provider' => 'spotify',
                    'provider_user_id' => $spotifyUser['id'] ?? $account->provider_user_id,
                ]);
            }

            return response()->json([
                'connected' => false,
                'provider' => 'spotify',
                'reason' => 'invalid_remote_token',
                'spotify_status' => $me->status(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'connected' => false,
                'provider' => 'spotify',
                'reason' => 'status_exception',
                'message' => $e->getMessage(),
            ]);
        }
    }
}