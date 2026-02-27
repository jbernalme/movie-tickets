<?php

namespace App\Data;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;

class MovieDetailsData extends Data
{
    public function __construct(
        public string $backdrop_path,
        public string $poster_path,
        public string $poster_thumbnail,
        public string $poster_url,
        public int $id,
        public string $slug,
        public array $genres,
        public string $title,
        public int $vote_average,
        public ?string $imdb_link,
        public ?string $overview,
        public ?string $release_date,
        public ?string $runtime,
        public array $credits,
        public array $videos,
        public array $gallery,
        public array $images,
        public array $backdrops,
        public array $crew,
        public array $director,
        public ?array $screenplay,
        public array $cast,
        public string $cast_str_list,
        public ?string $random_bg,
        public ?string $tagline,
        public ?string $year,
    ) {}

    public static function fromTmdb(array $movie): MovieDetailsData
    {
        $backdropPath = $movie['backdrop_path']
            ? 'https://www.themoviedb.org/t/p/original' .
                $movie['backdrop_path']
            : 'https://dummyimage.com/1271x715/000/fff';
        $posterUrl = $movie['poster_path']
            ? 'https://www.themoviedb.org/t/p/w440_and_h660_face' .
                $movie['poster_path']
            : 'https://via.placeholder.com/500x750';
        $posterThumbnail = $movie['poster_path']
            ? 'https://www.themoviedb.org/t/p/w220_and_h330_face' .
                $movie['poster_path']
            : 'https://dummyimage.com/220x330/20234f/7cdb29&text=No+Image';
        $voteAverage = $movie['vote_average'] * 10;
        $imdbLink = isset($movie['imdb_id'])
            ? 'https://www.imdb.com/title/' . $movie['imdb_id']
            : null;
        $releaseDate = Carbon::parse($movie['release_date'])->format('M d, Y');
        $runtime = self::timeToHoursMinutes($movie['runtime']);
        $genres = collect($movie['genres'])->pluck('name')->toArray();
        $title = $movie['title'];
        $cast = collect($movie['credits']['cast'])
            ->take(10)
            ->map(function ($cast) {
                return collect($cast)->merge([
                    'profile_path' => $cast['profile_path']
                        ? 'https://image.tmdb.org/t/p/w300' .
                            $cast['profile_path']
                        : 'https://via.placeholder.com/300x450',
                ]);
            })
            ->toArray();
        $castStrList = collect($movie['credits']['cast'])
            ->pluck('name')
            ->flatten()
            ->join(', ', ' y ');
        $crew = collect($movie['credits']['crew'])
            ->where('job', 'Director')
            ->values()
            ->toArray();
        $director = collect($movie['credits']['crew'])
            ->where('job', 'Director')
            ->values()
            ->toArray();
        $screenplay = collect($movie['credits']['crew'])
            ->where('job', 'Screenplay')
            ->values()
            ->toArray();
        $images = collect($movie['images']['backdrops'])->take(9)->toArray();
        $backdrops = collect($movie['images']['backdrops'])
            ->take(5)
            ->map(function ($bd) {
                return collect($bd)->merge([
                    'thumbnail' =>
                        'https://image.tmdb.org/t/p/w300/' . $bd['file_path'],
                    'w780' =>
                        'https://image.tmdb.org/t/p/w780/' . $bd['file_path'],
                    'w1280' =>
                        'https://image.tmdb.org/t/p/w1280/' . $bd['file_path'],
                    'original' =>
                        'https://image.tmdb.org/t/p/original/' .
                        $bd['file_path'],
                    'caption' =>
                        'Resolution: ' . $bd['width'] . 'x' . $bd['height'],
                ]);
            })
            ->toArray();
        $videos = collect($movie['videos']['results'])
            ->take(5)
            ->map(function ($video) {
                return collect($video)->merge([
                    'url' =>
                        $video['site'] === 'YouTube'
                            ? 'https://www.youtube.com/watch?v=' . $video['key']
                            : $video['key'],
                ]);
            })
            ->toArray();
        $gallery = [
            'images' => collect($movie['images']['backdrops'])
                ->take(10)
                ->map(function ($image) {
                    return [
                        'thumbnail' =>
                            'https://image.tmdb.org/t/p/w300/' .
                            $image['file_path'],
                        'source' =>
                            'https://image.tmdb.org/t/p/original/' .
                            $image['file_path'],
                    ];
                }),
            'videos' => collect($movie['videos']['results'])
                ->take(10)
                ->map(function ($video) {
                    return [
                        'thumbnail' =>
                            'https://img.youtube.com/vi/' .
                            $video['key'] .
                            '/mqdefault.jpg',
                        'source' => $video['key'],
                    ];
                }),
        ];
        $randomBg = $movie['images']['backdrops']
            ? 'https://image.tmdb.org/t/p/w1280' .
                collect($movie['images']['backdrops'])->random()['file_path']
            : '';
        $tagline = $movie['tagline'];
        $year = Carbon::parse($movie['release_date'])->format('Y');

        return new self(
            backdrop_path: $backdropPath,
            poster_path: $movie['poster_path'] ?? '',
            poster_thumbnail: $posterThumbnail,
            poster_url: $posterUrl,
            id: $movie['id'],
            slug: Str::slug($movie['title']),
            genres: $genres,
            title: $title,
            vote_average: $voteAverage,
            imdb_link: $imdbLink,
            overview: $movie['overview'],
            release_date: $releaseDate,
            runtime: $runtime,
            credits: $movie['credits'],
            videos: $videos,
            gallery: $gallery,
            images: $images,
            backdrops: $backdrops,
            crew: $crew,
            director: $director,
            screenplay: $screenplay ?? [],
            cast: $cast,
            cast_str_list: $castStrList,
            random_bg: $randomBg,
            tagline: $tagline,
            year: $year,
        );
    }

    private static function timeToHoursMinutes($time, $format = '%02dh %02dmin')
    {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = $time % 60;
        return sprintf($format, $hours, $minutes);
    }
}
