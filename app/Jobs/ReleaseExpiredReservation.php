<?php

namespace App\Jobs;

use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReleaseExpiredReservation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $ticketId;
    public $seatIds;

    /**
     * Create a new job instance.
     */
    public function __construct($ticketId, $seatIds)
    {
        $this->ticketId = $ticketId;
        $this->seatIds = $seatIds;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('ReleaseExpiredReservation: Ejecutando', [
            'ticket_id' => $this->ticketId,
            'seat_ids' => $this->seatIds,
        ]);

        $ticket = Ticket::find($this->ticketId);

        if (!$ticket || $ticket->status !== 'pending') {
            Log::info(
                'ReleaseExpiredReservation: Ticket no pendiente o no existe',
                [
                    'ticket_id' => $this->ticketId,
                    'status' => $ticket?->status,
                ],
            );
            return;
        }

        \DB::transaction(function () use ($ticket) {
            $ticket->update(['status' => 'cancelled']);

            Seat::whereIn('id', $this->seatIds)->update([
                'status' => 'available',
            ]);
        });
    }
}
