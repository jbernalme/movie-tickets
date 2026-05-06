<?php

namespace App\Http\Controllers;

use App\Jobs\ReleaseExpiredReservation;
use App\Models\Screening;
use App\Models\Seat;
use App\Models\Ticket;
use App\Services\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function __construct(private DiscountService $discountService) {}

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
        $validated = $request->validate([
            'screening_id' => 'required|exists:screenings,id',
            'seat_ids' => 'required|array|min:1|max:10',
            'seat_ids.*' => 'required|integer|exists:seats,id',
            'discount_code' => 'nullable|string|max:20',
        ]);

        try {
            $ticket = DB::transaction(function () use ($validated) {
                $screening = Screening::findOrFail($validated['screening_id']);

                $seats = Seat::where('hall_id', $screening->hall_id)
                    ->whereIn('id', $validated['seat_ids'])
                    ->with('seatType')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                // Validar que TODOS los seats solicitados existen en este hall
                if ($seats->count() !== count($validated['seat_ids'])) {
                    throw new \Exception(
                        'Uno o más asientos no pertenecen a esta sala',
                    );
                }

                // Validar disponibilidad
                if ($seats->contains('status', 'unavailable')) {
                    throw new \Exception('Asientos no disponibles');
                }

                $subtotal = $seats->sum(function ($seat) use ($screening) {
                    return $seat->seatType->price_multiplier *
                        $screening->base_price;
                });

                $total = $subtotal;
                $validDiscount = null;

                if (!empty($validated['discount_code'])) {
                    $validDiscount = $this->discountService->getValidDiscount(
                        $validated['discount_code'],
                    );

                    if ($validDiscount) {
                        $total = $this->discountService->applyDiscount(
                            $validDiscount,
                            $subtotal,
                        );
                    }
                }

                Log::info('Discount applied', [
                    'code' => $validated['discount_code'] ?? null,
                    'valid' => $validDiscount !== null,
                    'subtotal' => $subtotal,
                    'total' => $total,
                ]);

                // Crear ticket
                // TODO: Usar el user_id del usuario autenticado
                $ticket = Ticket::create([
                    'user_id' => 1,
                    'screening_id' => $validated['screening_id'],
                    'discount_id' => $validDiscount?->id,
                    'ticket_code' => 'TK' . time() . rand(1000, 9999),
                    'total_price' => $this->discountService->formatTotal(
                        $subtotal,
                    ),
                    'discount_price' => $this->discountService->formatTotal(
                        $subtotal - $total,
                    ),
                    'final_price' => $this->discountService->formatTotal(
                        $total,
                    ),
                    'status' => 'pending',
                    'expires_at' => now()->addMinutes(
                        config('tickets.expiration_minutes'),
                    ),
                    'used_at' => null,
                ]);

                foreach ($seats as $seat) {
                    $seatPrice =
                        $seat->seatType->price_multiplier *
                        $screening->base_price;

                    $ticket->seats()->attach($seat->id, [
                        'price' => $seatPrice,
                    ]);
                }

                // Marcar asientos como ocupados
                Seat::where('hall_id', $screening->hall_id)
                    ->whereIn('id', $validated['seat_ids'])
                    ->update(['status' => 'unavailable']);

                // Dispatch job con delay para liberación automática
                ReleaseExpiredReservation::dispatch(
                    $ticket->id,
                    $validated['seat_ids'],
                )
                    ->delay(
                        now()->addMinutes(config('tickets.expiration_minutes')),
                    )
                    ->onQueue('reservations');
                return $ticket;
            });
            return response()->json(['ticket' => $ticket]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
