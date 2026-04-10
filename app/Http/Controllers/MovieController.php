<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Movie;
use Illuminate\Http\Request;
use App\Services\TmdbService;
use App\Data\MovieDetailsData;
use App\Data\ScreeningsByMonthData;

class MovieController extends Controller
{
    public function __construct(private TmdbService $tmdbService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $movieId)
    {
        $movie = Movie::with([
            'screenings' => function ($query) {
                $query
                    ->where('status', '!=', 'finished')
                    ->orderBy('start_time');
            },
        ])->findOrFail($movieId);

        $screenForMonth = ScreeningsByMonthData::fromScreeningsCollection(
            $movie->screenings,
        );

        $movieTmdb = $this->tmdbService->getMovie($movieId);

        return Inertia::render('movie/show', [
            'movie' => MovieDetailsData::from($movieTmdb),
            'screenings' => $screenForMonth,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        //
    }
}
