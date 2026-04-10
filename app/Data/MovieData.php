<?php

namespace App\Data;

use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class MovieData extends Data
{
    public function __construct(
        // 🔹 Requeridos
        public int $id,
        public string $title,
        public string $slug,
        public ?string $overview,
        public string $backdrop_path,
        public string $poster_path,
        public string $poster_thumbnail,
        public int $vote_average,
        public string $release_date,
        public string $year,
        public array|string $genres,
        public int $tmdb_id,
        public string $original_title,

        // 🔹 Opcionales: orden correcto según docs v4
        public Optional|null|string $imdb_id = null,
        public Optional|null|int $runtime = null,
        public Optional|null|string $tagline = null,
        public Optional|null|string $status = null,
    ) {}

    public static function fromTmdb(array $movie, array $genresMap = []): self
    {
        $releaseDate = $movie['release_date'] ?? null;
        $genreNames = collect($movie['genre_ids'] ?? [])
            ->map(fn($id) => $genresMap[$id] ?? null)
            ->filter()
            ->values()
            ->toArray();

        // ✅ Opción recomendada: NO pasar los opcionales → usan default null
        return new self(
            id: $movie['id'],
            tmdb_id: $movie['id'],
            title: $movie['title'],
            original_title: $movie['original_title'],
            slug: Str::slug($movie['title']),
            overview: $movie['overview'] ?? null,
            backdrop_path: self::buildBackdropUrl($movie['backdrop_path']),
            poster_path: self::buildPosterUrl($movie['poster_path'], 'w440'),
            poster_thumbnail: self::buildPosterUrl(
                $movie['poster_path'],
                'w220',
            ),
            vote_average: (int) ($movie['vote_average'] * 10),
            release_date: $releaseDate
                ? Carbon::parse($releaseDate)->format('M d, Y')
                : 'n/a',
            year: $releaseDate
                ? Carbon::parse($releaseDate)->format('Y')
                : 'n/a',
            genres: $genreNames,
            // ✅ No pases imdb_id, runtime, etc. → PHP usa = null, LaravelData los omite
        );
    }

    public static function fromDb(Movie $movie): self
    {
        $releaseDate = $movie->release_date ?? null;

        return new self(
            id: $movie->id,
            tmdb_id: $movie->tmdb_id,
            title: $movie->title,
            original_title: $movie->original_title,
            slug: $movie->slug,
            overview: $movie->overview ?? null,
            backdrop_path: self::buildBackdropUrl($movie->backdrop_path),
            poster_path: self::buildPosterUrl($movie->poster_path, 'w440'),
            poster_thumbnail: self::buildPosterUrl($movie->poster_path, 'w220'),
            vote_average: (int) ($movie->vote_average * 10),
            release_date: $releaseDate
                ? Carbon::parse($releaseDate)->format('M d, Y')
                : 'n/a',
            year: $releaseDate
                ? Carbon::parse($releaseDate)->format('Y')
                : 'n/a',
            genres: $movie->genres,
            imdb_id: $movie->imdb_id ?? null,
            runtime: $movie->runtime ?? null,
            tagline: $movie->tagline ?? null,
            status: $movie->status ?? null,
        );
    }

    private static function buildBackdropUrl(?string $path): string
    {
        return $path
            ? 'https://www.themoviedb.org/t/p/original' . $path
            : 'https://dummyimage.com/1271x715/000/fff';
    }

    private static function buildPosterUrl(
        ?string $path,
        string $size = 'w440',
    ): string {
        if (!$path) {
            return 'https://dummyimage.com/440x660/20234f/7cdb29&text=No+Image';
        }

        $sizes = [
            'w440' => 'w440_and_h660_face',
            'w220' => 'w220_and_h330_face',
        ];

        return 'https://www.themoviedb.org/t/p/' .
            ($sizes[$size] ?? 'original') .
            $path;
    }
}
