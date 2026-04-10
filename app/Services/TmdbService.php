<?php

namespace App\Services;

use App\Data\MovieData;
use Illuminate\Support\Facades\Http;

class TmdbService
{
    private $apiKey;
    private $baseUrl;
    private $language = 'en-US';

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.token');
        $this->baseUrl = config('services.tmdb.url');
    }

    public function getMovie($movieId): array
    {
        $imageLanguage = 'en,es,null';
        $appendResponse = 'credits,videos,images';

        /** @var \Illuminate\Http\Client\Response $response */
        $response = Http::withToken($this->apiKey)
            ->withQueryParameters([
                'language' => $this->language,
                'append_to_response' => $appendResponse,
                'include_image_language' => $imageLanguage,
            ])
            ->get("{$this->baseUrl}/movie/{$movieId}");

        return $response->json();
    }

    public function buildTrendingRequest($pool)
    {
        return $pool
            ->as('trending')
            ->withToken($this->apiKey)
            ->withQueryParameters(['language' => $this->language])
            ->get("{$this->baseUrl}/trending/movie/week");
    }

    public function buildNowPlayingRequest($pool)
    {
        return $pool
            ->as('now_playing')
            ->withToken($this->apiKey)
            ->withQueryParameters(['language' => $this->language])
            ->get("{$this->baseUrl}/movie/now_playing");
    }
    public function buildUpcomingRequest($pool)
    {
        return $pool
            ->as('upcoming')
            ->withToken($this->apiKey)
            ->withQueryParameters(['language' => $this->language])
            ->get("{$this->baseUrl}/movie/upcoming");
    }
    public function buildGenresRequest($pool)
    {
        return $pool
            ->as('genres')
            ->withToken($this->apiKey)
            ->withQueryParameters(['language' => $this->language])
            ->get("{$this->baseUrl}/genre/movie/list");
    }

    public function createGenresMap(array $genres): array
    {
        return collect($genres)->pluck('name', 'id')->toArray();
    }

    public function getGenreNames(array $genreIds, array $genresMap): array
    {
        return collect($genreIds)
            ->map(fn($id) => $genresMap[$id] ?? null)
            ->filter()
            ->values()
            ->toArray();
    }

    public function formatMovieResponse($response, array $genresMap = []): array
    {
        if (!$response->successful()) {
            return [
                'results' => [],
                'page' => 1,
                'total_pages' => 0,
                'total_results' => 0,
            ];
        }

        $data = $response->json();

        return [
            'results' => array_map(
                fn($movie) => MovieData::fromTmdb($movie, $genresMap),
                $data['results'] ?? [],
            ),
            'page' => $data['page'] ?? 1,
            'total_pages' => $data['total_pages'] ?? 0,
            'total_results' => $data['total_results'] ?? 0,
        ];
    }
}
