<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Illuminate\Http\Request;
use App\Models\Screening;
use Inertia\Inertia;
use Inertia\Response;

class SeatController extends Controller
{
    public function select($slug, Screening $screening): Response
    {
        $screening->load('hall.seats.seatType');

        $seatsByRows = $screening->hall->seats
            ->groupBy('row')
            ->map(
                fn($rowSeats) => [
                    'row' => $rowSeats->first()->row,
                    'seats' => $rowSeats->sortBy('number')->values()->toArray(),
                ],
            )
            ->values()
            ->toArray();
        // dump($seatsByRows);
        return Inertia::render(
            'seat/select',
            compact('seatsByRows', 'screening'),
        );
    }

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
    public function show(Seat $seat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seat $seat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seat $seat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seat $seat)
    {
        //
    }
}
