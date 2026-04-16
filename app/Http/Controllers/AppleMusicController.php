<?php

namespace App\Http\Controllers;

use App\Models\ExternalAccount;
use App\Services\AppleMusicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AppleMusicController extends Controller
{
    protected AppleMusicService $appleMusicService;

    public function __construct(AppleMusicService $appleMusicService)
    {
        $this->appleMusicService = $appleMusicService;
    }

    public function token()
    {
        try {
            return response()->json([
                'token' => $this->appleMusicService->generateDeveloperToken()
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al generar token de Apple Music',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function connect(Request $request)
    {
        try {
            $request->validate([
                'music_user_token' => ['required', 'string'],
                'provider_user_id' => ['nullable', 'string'],
                'scopes' => ['nullable', 'array'],
            ]);

            $user = $request->user();

            $externalAccount = ExternalAccount::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'provider' => 'apple_music',
                ],
                [
                    'provider_user_id' => $request->provider_user_id,
                    'access_token' => $request->music_user_token,
                    'refresh_token' => null,
                    'token_expires_at' => null,
                    'scopes' => $request->scopes ?? ['musickit_web'],
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Apple Music conectado correctamente',
                'external_account' => $externalAccount,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al guardar conexión de Apple Music',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function status(Request $request)
    {
        $connected = ExternalAccount::where('user_id', $request->user()->id)
            ->where('provider', 'apple_music')
            ->exists();

        return response()->json([
            'connected' => $connected,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    protected function buildArtworkUrl(?string $url, int $w = 300, int $h = 300, string $format = 'jpg'): ?string
    {
        if (!$url) {
            return null;
        }

        return str_replace(
            ['{w}', '{h}', '{f}', '{c}'],
            [$w, $h, $format, 'bb'],
            $url
        );
    }

    protected function getDeveloperHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->appleMusicService->generateDeveloperToken(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CATÁLOGO APPLE MUSIC
    |--------------------------------------------------------------------------
    */

    public function search(Request $request)
    {
        try {
            $request->validate([
                'q' => ['required', 'string'],
                'storefront' => ['nullable', 'string'],
                'limit' => ['nullable', 'integer', 'min:1', 'max:25'],
            ]);

            $data = $this->appleMusicService->search(
                $request->q,
                $request->get('storefront', 'us'),
                ['songs', 'albums', 'artists'],
                $request->get('limit', 10)
            );

            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al buscar en Apple Music',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function song(Request $request, string $id)
    {
        try {
            $data = $this->appleMusicService->getSong(
                $id,
                $request->get('storefront', 'us')
            );

            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener canción',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function album(Request $request, string $id)
    {
        try {
            $data = $this->appleMusicService->getAlbum(
                $id,
                $request->get('storefront', 'us')
            );

            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener álbum',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function albumTracks(Request $request, string $id)
    {
        try {
            $storefront = $request->get('storefront', 'us');

            $response = Http::withHeaders($this->getDeveloperHeaders())
                ->get("https://api.music.apple.com/v1/catalog/{$storefront}/albums/{$id}", [
                    'include' => 'tracks',
                ]);

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener tracks del álbum',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function artist(Request $request, string $id)
    {
        try {
            $data = $this->appleMusicService->getArtist(
                $id,
                $request->get('storefront', 'us')
            );

            return response()->json($data);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener artista',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function playlist(Request $request, string $id)
    {
        try {
            $storefront = $request->get('storefront', 'us');

            $response = Http::withHeaders($this->getDeveloperHeaders())
                ->get("https://api.music.apple.com/v1/catalog/{$storefront}/playlists/{$id}");

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener playlist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function playlistTracks(Request $request, string $id)
    {
        try {
            $storefront = $request->get('storefront', 'us');

            $response = Http::withHeaders($this->getDeveloperHeaders())
                ->get("https://api.music.apple.com/v1/catalog/{$storefront}/playlists/{$id}", [
                    'include' => 'tracks',
                ]);

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener tracks de la playlist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function charts(Request $request)
    {
        try {
            $storefront = $request->get('storefront', 'us');

            $response = Http::withHeaders($this->getDeveloperHeaders())
                ->get("https://api.music.apple.com/v1/catalog/{$storefront}/charts", [
                    'types' => $request->get('types', 'songs,albums,playlists'),
                    'limit' => $request->get('limit', 10),
                ]);

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener charts',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function landingArtworks(Request $request)
    {
        try {
            $storefront = $request->get('storefront', 'us');
            $limit = (int) $request->get('limit', 40);
            $limit = max(6, min($limit, 30));

            $response = Http::withHeaders($this->getDeveloperHeaders())
                ->get("https://api.music.apple.com/v1/catalog/{$storefront}/charts", [
                    'types' => 'songs,albums',
                    'limit' => $limit,
                ]);

            if (!$response->ok()) {
                return response()->json([
                    'message' => 'Error al obtener artworks para la landing',
                    'status' => $response->status(),
                    'error' => $response->json(),
                ], $response->status());
            }

            $json = $response->json();
            $results = $json['results'] ?? [];

            $items = collect();

            foreach (['songs', 'albums'] as $type) {
                $chartGroups = $results[$type] ?? [];

                foreach ($chartGroups as $group) {
                    $data = $group['data'] ?? [];

                    foreach ($data as $entry) {
                        $attributes = $entry['attributes'] ?? [];
                        $artworkUrl = $attributes['artwork']['url'] ?? null;

                        $image = $this->buildArtworkUrl($artworkUrl, 400, 400, 'jpg');

                        if (!$image) {
                            continue;
                        }

                        $items->push([
                            'id' => $entry['id'] ?? uniqid('apple_', true),
                            'type' => $type,
                            'title' => $attributes['name'] ?? '',
                            'subtitle' => $attributes['artistName'] ?? '',
                            'image' => $image,
                        ]);
                    }
                }
            }

            $uniqueItems = $items
                ->unique(fn ($item) => $item['image'])
                ->take($limit)
                ->values();

            return response()->json([
                'items' => $uniqueItems,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener artworks de la landing',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | BIBLIOTECA DEL USUARIO (/me/library)
    |--------------------------------------------------------------------------
    */

    protected function getMusicUserToken(Request $request): string
    {
        $account = ExternalAccount::where('user_id', $request->user()->id)
            ->where('provider', 'apple_music')
            ->first();

        if (!$account || !$account->access_token) {
            throw new \Exception('No se encontró un Music User Token para este usuario.');
        }

        return $account->access_token;
    }

    protected function meLibraryRequest(Request $request, string $endpoint, array $query = [])
    {
        $musicUserToken = $this->getMusicUserToken($request);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->appleMusicService->generateDeveloperToken(),
            'Music-User-Token' => $musicUserToken,
        ])->get("https://api.music.apple.com/v1/me/library/{$endpoint}", $query);

        return response()->json($response->json(), $response->status());
    }

    public function libraryAlbums(Request $request)
    {
        try {
            return $this->meLibraryRequest($request, 'albums', [
                'limit' => $request->get('limit', 100),
                'offset' => $request->get('offset', 0),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener álbumes de la biblioteca',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function libraryPlaylists(Request $request)
    {
        try {
            return $this->meLibraryRequest($request, 'playlists', [
                'limit' => $request->get('limit', 100),
                'offset' => $request->get('offset', 0),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener playlists de la biblioteca',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function libraryAlbum(Request $request, string $id)
    {
        try {
            return $this->meLibraryRequest($request, "albums/{$id}", [
                'include' => 'tracks',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener álbum de la biblioteca',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function libraryPlaylist(Request $request, string $id)
    {
        try {
            return $this->meLibraryRequest($request, "playlists/{$id}", [
                'include' => 'tracks',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener playlist de la biblioteca',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function demoAlbums(Request $request)
    {
        try {
            $storefront = $request->get('storefront', 'us');

            $response = Http::withHeaders($this->getDeveloperHeaders())
                ->get("https://api.music.apple.com/v1/catalog/{$storefront}/charts", [
                    'types' => 'albums',
                    'limit' => 25,
                ]);

            if (! $response->ok()) {
                return response()->json([
                    'message' => 'Error al obtener álbumes demo',
                    'status' => $response->status(),
                    'error' => $response->json(),
                ], $response->status());
            }

            $albums = $response->json('results.albums.0.data', []);

            // shuffle($albums);

            return response()->json([
                'data' => array_slice($albums, 0, 15),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al cargar álbumes demo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function demoPlaylists(Request $request)
    {
        try {
            $storefront = $request->get('storefront', 'us');

            $response = Http::withHeaders($this->getDeveloperHeaders())
                ->get("https://api.music.apple.com/v1/catalog/{$storefront}/charts", [
                    'types' => 'playlists',
                    'limit' => 25,
                ]);

            if (! $response->ok()) {
                return response()->json([
                    'message' => 'Error al obtener playlists demo',
                    'status' => $response->status(),
                    'error' => $response->json(),
                ], $response->status());
            }

            $playlists = $response->json('results.playlists.0.data', []);

            // shuffle($playlists);

            return response()->json([
                'data' => array_slice($playlists, 0, 15),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al cargar playlists demo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}