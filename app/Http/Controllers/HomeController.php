<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Movie;
use App\Data\MovieData;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Services\TmdbService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(TmdbService $tmdbService)
    {
        $movies = Cache::remember('home_movies', 3600, function () use (
            $tmdbService,
        ) {
            $movies = Http::pool(
                fn($pool) => [
                    $tmdbService->buildTrendingRequest($pool),
                    $tmdbService->buildNowPlayingRequest($pool),
                    $tmdbService->buildUpcomingRequest($pool),
                    $tmdbService->buildGenresRequest($pool),
                ],
            );
            return [
                'trending' => $this->formatMovieResponse($movies['trending']),
                'now_playing' => $this->formatMovieResponse(
                    $movies['now_playing'],
                ),
                'upcoming' => $this->formatMovieResponse($movies['upcoming']),
                'genres' => $movies['genres']->successful()
                    ? $movies['genres']->json()['genres']
                    : [],
            ];
        });

        return Inertia::render('home', ['movies' => $movies]);
    }

    private function formatMovieResponse($response): array
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
                fn($movie) => MovieData::fromTmdb($movie),
                $data['results'] ?? [],
            ),
            'page' => $data['page'] ?? 1,
            'total_pages' => $data['total_pages'] ?? 0,
            'total_results' => $data['total_results'] ?? 0,
        ];
    }
}
