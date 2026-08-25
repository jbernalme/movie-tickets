<?php

namespace App\Http\Controllers;

use App\Actions\Tickets\CreateTicketReservation;
use App\Data\TicketReservationData;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index(
        Request $request,
        CreateTicketReservation $createTicketReservation,
    ) {
        $validated = $request->validate([
            'screening_id' => 'required|exists:screenings,id',
            'seat_ids' => 'required|array|min:1|max:10',
            'seat_ids.*' => 'required|integer|exists:seats,id',
            'discount_code' => 'nullable|string|max:20',
        ]);

        $data = TicketReservationData::fromRequest($validated, auth()->id());

        $ticket = $createTicketReservation($data);

        $ticket->load(['screening.movie', 'seats.seatType', 'screening.hall']);

        return Inertia::render('checkout', [
            'ticket' => $ticket,
        ]);
    }
}
