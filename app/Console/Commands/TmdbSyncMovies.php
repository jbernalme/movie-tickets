<?php

namespace App\Console\Commands;

use App\Models\Movie;
use App\Services\TmdbService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class TmdbSyncMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tmdb-sync-movies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronize movies from TMDB';

    /**
     * Execute the console command.
     */
    public function handle(TmdbService $tmdbService)
    {
        $movies = Http::pool(
            fn($pool) => [
                $tmdbService->buildNowPlayingRequest($pool),
                $tmdbService->buildUpcomingRequest($pool),
                $tmdbService->buildTrendingRequest($pool),
            ],
        );

        $concatMovies = $this->transformMoviesWithStatus($movies);

        foreach ($concatMovies as $movie) {
            $this->info("Processing movie ID: {$movie['id']}");
            $this->syncMovie($tmdbService, $movie);
        }

        $this->info('Movies synced successfully');
    }

    protected function transformMoviesWithStatus(array $movies): Collection
    {
        return collect($movies['upcoming']->json()['results'] ?? [])
            ->map(fn($movie) => $movie + ['status' => 'upcoming'])
            ->concat(
                collect($movies['now_playing']->json()['results'] ?? [])->map(
                    fn($movie) => $movie + ['status' => 'now_playing'],
                ),
            )
            ->concat(
                collect($movies['trending']->json()['results'] ?? [])->map(
                    fn($movie) => $movie + ['status' => 'trending'],
                ),
            );
    }

    protected function syncMovie(TmdbService $tmdbService, array $movie): void
    {
        $movieRes = $tmdbService->getMovie($movie['id']);

        Movie::updateOrCreate(
            ['tmdb_id' => $movie['id']],
            [
                'title' => $movieRes['title'],
                'original_title' => $movieRes['original_title'],
                'overview' => $movieRes['overview'],
                'poster_path' => $movieRes['poster_path'],
                'genres' => $this->formatGenres($movieRes['genres'] ?? []),
                'release_date' => $movieRes['release_date'],
                'imdb_id' => $movieRes['imdb_id'],
                'runtime' => $movieRes['runtime'],
                'tagline' => $movieRes['tagline'],
                'status' => $movie['status'],
                'last_synced_at' => now(),
                'needs_detail_sync' => false,
                'backdrop_path' => $movieRes['backdrop_path'],
            ],
        );
    }

    protected function formatGenres(array $genres): string
    {
        return collect($genres)->pluck('name')->implode(',');
    }
}
