<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class TmdbService
{
    private $apiKey;
    private $baseUrl;
    private $language = 'es-mx';

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.token');
        $this->baseUrl = config('services.tmdb.url');
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
}
