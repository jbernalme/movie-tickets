<?php
// app/Data/TicketReservationResultData.php
namespace App\Data;

use App\Models\Ticket;
use Spatie\LaravelData\Data;

class TicketReservationResultData extends Data
{
    public function __construct(
        public readonly int $ticketId,
        public readonly string $ticketCode,
        public readonly float $subtotal,
        public readonly float $discountApplied,
        public readonly float $total,
        public readonly string $status,
        public readonly string $expiresAt,
        public readonly array $seats,
        public readonly ?DiscountData $discount = null,
    ) {}

    // Factory desde el modelo Ticket
    public static function fromModel(
        Ticket $ticket,
        float $subtotal,
        float $discountApplied,
    ): self {
        return new self(
            ticketId: $ticket->id,
            ticketCode: $ticket->ticket_code,
            subtotal: $subtotal,
            discountApplied: $discountApplied,
            total: $ticket->final_price,
            status: $ticket->status,
            expiresAt: $ticket->expires_at->toIso8601String(),
            seats: $ticket->seats
                ->map(
                    fn($seat) => [
                        'id' => $seat->id,
                        'row' => $seat->row,
                        'number' => $seat->number,
                        'price' => $seat->pivot->price,
                    ],
                )
                ->toArray(),
            discount: $ticket->discount
                ? DiscountData::fromModel($ticket->discount)
                : null,
        );
    }
}
