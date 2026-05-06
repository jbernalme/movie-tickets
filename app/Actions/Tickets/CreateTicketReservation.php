<?php
// app/Actions/Tickets/CreateTicketReservation.php
namespace App\Actions\Tickets;

use App\Data\TicketReservationData;
use App\Data\TicketReservationResultData;
use App\Models\Screening;
use App\Models\Seat;
use App\Models\Ticket;
use App\Services\DiscountService;
use App\Jobs\ReleaseExpiredReservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateTicketReservation
{
    public function __construct(private DiscountService $discountService) {}

    /**
     * Ejecuta la reserva de tickets usando DTOs
     */
    public function __invoke(TicketReservationData $data): Ticket
    {
        return DB::transaction(function () use ($data) {
            $screening = Screening::findOrFail($data->screeningId);

            // Obtener y bloquear asientos
            $seats = Seat::where('hall_id', $screening->hall_id)
                ->whereIn('id', $data->seatIds)
                ->with('seatType')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            // Validar que todos los asientos existen
            if ($seats->count() !== count($data->seatIds)) {
                throw new \Exception(
                    'Uno o más asientos no pertenecen a esta sala',
                );
            }

            // Validar disponibilidad
            if ($seats->contains('status', 'unavailable')) {
                throw new \Exception('Asientos no disponibles');
            }

            // Calcular subtotal
            $subtotal = $seats->sum(function ($seat) use ($screening) {
                return $seat->seatType->price_multiplier *
                    $screening->base_price;
            });

            // Aplicar descuento
            $validDiscount = null;
            $total = $subtotal;

            if ($data->discountCode) {
                $validDiscount = $this->discountService->getValidDiscount(
                    $data->discountCode,
                );
                if ($validDiscount) {
                    $total = $this->discountService->applyDiscount(
                        $validDiscount,
                        $subtotal,
                    );
                }
            }

            $discountApplied = $subtotal - $total;

            Log::info('Discount applied in reservation', [
                'code' => $data->discountCode,
                'valid' => $validDiscount !== null,
                'subtotal' => $subtotal,
                'total' => $total,
                'user_id' => $data->userId,
            ]);

            // Crear ticket
            $ticket = Ticket::create([
                'user_id' => $data->userId,
                'screening_id' => $data->screeningId,
                'discount_id' => $validDiscount?->id,
                'ticket_code' => $this->generateTicketCode(),
                'total_price' => $this->discountService->formatTotal($total),
                'discount_price' => $this->discountService->formatTotal(
                    $discountApplied,
                ),
                'final_price' => $this->discountService->formatTotal($total),
                'status' => 'pending',
                'expires_at' => now()->addMinutes(
                    config('tickets.expiration_minutes'),
                ),
                'used_at' => null,
            ]);

            // Asociar asientos
            foreach ($seats as $seat) {
                $seatPrice =
                    $seat->seatType->price_multiplier * $screening->base_price;
                $ticket->seats()->attach($seat->id, ['price' => $seatPrice]);
            }

            // Marcar asientos como no disponibles
            Seat::where('hall_id', $screening->hall_id)
                ->whereIn('id', $data->seatIds)
                ->update(['status' => 'unavailable']);

            // Programar liberación automática
            ReleaseExpiredReservation::dispatch($ticket->id, $data->seatIds)
                ->delay(
                    now()->addMinutes(config('tickets.expiration_minutes', 15)),
                )
                ->onQueue('reservations');

            // Retornar DTO con el resultado
            // return TicketReservationResultData::fromModel(
            //     $ticket,
            //     $subtotal,
            //     $discountApplied,
            // );

            return $ticket;
        });
    }

    private function generateTicketCode(): string
    {
        return 'TK' . time() . rand(1000, 9999);
    }
}
