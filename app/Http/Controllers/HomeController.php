<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Movie;
use Inertia\Response;
use App\Data\MovieData;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Services\TmdbService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(TmdbService $tmdbService): Response
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

            $genresData = $movies['genres']->successful()
                ? $movies['genres']->json()['genres']
                : [];

            $genresMap = $tmdbService->createGenresMap($genresData);

            return [
                'trending' => $this->formatMovieResponse(
                    $movies['trending'],
                    $genresMap,
                ),
                'now_playing' => $this->formatMovieResponse(
                    $movies['now_playing'],
                    $genresMap,
                ),
                'upcoming' => $this->formatMovieResponse(
                    $movies['upcoming'],
                    $genresMap,
                ),
                'genres' => $movies['genres']->successful()
                    ? $movies['genres']->json()['genres']
                    : [],
            ];
        });

        return Inertia::render('home', ['movies' => $movies]);
    }

    private function formatMovieResponse(
        $response,
        array $genresMap = [],
    ): array {
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
