<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    public function index()
    {
        $tickets = auth()
            ->user()
            ->tickets()
            ->with(['screening.movie'])
            ->get();
        return Inertia::render('reservations', [
            'tickets' => $tickets,
        ]);
    }
}
