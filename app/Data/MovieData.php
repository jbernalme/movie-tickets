<?php

namespace App\Data;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;

class MovieData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public string $name,
        public string $slug,
        public ?string $overview,
        public string $backdrop_path,
        public string $poster_path,
        public string $poster_thumbnail,
        public string $vote_average,
        public string $release_date,
        public string $year,
        public array $genre_ids,
        public array $genres,
    ) {}

    public static function fromTmdb(array $movie, array $genresMap = []): self
    {
        $backdropPath = $movie['backdrop_path']
            ? 'https://www.themoviedb.org/t/p/original' .
                $movie['backdrop_path']
            : 'https://dummyimage.com/1271x715/000/fff';
        $posterPath = $movie['poster_path']
            ? 'https://www.themoviedb.org/t/p/w440_and_h660_face' .
                $movie['poster_path']
            : 'https://dummyimage.com/440x660/20234f/7cdb29&text=No+Image';

        $posterThumbnail = $movie['poster_path']
            ? 'https://www.themoviedb.org/t/p/w220_and_h330_face' .
                $movie['poster_path']
            : 'https://dummyimage.com/220x330/20234f/7cdb29&text=No+Image';

        $releaseDate = $movie['release_date'] ?? null;

        $genreNames = collect($movie['genre_ids'] ?? [])
            ->map(fn($id) => $genresMap[$id] ?? null)
            ->filter()
            ->values()
            ->toArray();

        return new self(
            id: $movie['id'],
            title: $movie['title'],
            name: $movie['title'],
            slug: Str::slug($movie['title']),
            overview: $movie['overview'] ?? null,
            backdrop_path: $backdropPath,
            poster_path: $posterPath,
            poster_thumbnail: $posterThumbnail,
            vote_average: round($movie['vote_average'] * 10) . '%',
            release_date: $releaseDate
                ? Carbon::parse($releaseDate)->format('M d, Y')
                : 'n/a',
            year: $releaseDate
                ? Carbon::parse($releaseDate)->format('Y')
                : 'n/a',
            genre_ids: $movie['genre_ids'] ?? [],
            genres: $genreNames,
        );
    }
}
