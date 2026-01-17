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

    public function getNowPlayingMovies()
    {
        $responses = Http::pool(
            fn(Pool $pool) => [
                $pool
                    ->as('trendingMovie')
                    ->withToken($this->apiKey)
                    ->get("{$this->baseUrl}/trending/movie/week", [
                        'language' => $this->language,
                    ]),
                $pool
                    ->as('popularMovie')
                    ->withToken($this->apiKey)
                    ->get("{$this->baseUrl}/movie/popular", [
                        'language' => $this->language,
                    ]),
                $pool
                    ->as('nowPlayingMovie')
                    ->withToken($this->apiKey)
                    ->get("{$this->baseUrl}/movie/now_playing", [
                        'language' => $this->language,
                    ]),
                $pool
                    ->as('topRatedMovie')
                    ->withToken($this->apiKey)
                    ->get("{$this->baseUrl}/movie/top_rated", [
                        'language' => $this->language,
                    ]),
                $pool
                    ->as('genres')
                    ->withToken($this->apiKey)
                    ->get("{$this->baseUrl}/genre/movie/list", [
                        'language' => $this->language,
                    ]),
            ],
        );

        $mapped = Arr::map($responses, function ($value, $key) {
            return $key === 'genres'
                ? $value->json()['genres']
                : $value->json();
        });

        return $mapped;
    }

    public function buildTrendingRequest($pool)
    {
        return $pool
            ->as('trending')
            ->withToken($this->apiKey)
            ->get("{$this->baseUrl}/trending/movie/week");
    }

    public function buildNowPlayingRequest($pool)
    {
        return $pool
            ->as('now_playing')
            ->withToken($this->apiKey)
            ->get("{$this->baseUrl}/movie/now_playing");
    }
    public function buildUpcomingRequest($pool)
    {
        return $pool
            ->as('upcoming')
            ->withToken($this->apiKey)
            ->get("{$this->baseUrl}/movie/upcoming");
    }
    public function buildGenresRequest($pool)
    {
        return $pool
            ->as('genres')
            ->withToken($this->apiKey)
            ->get("{$this->baseUrl}/genre/movie/list");
    }
}
