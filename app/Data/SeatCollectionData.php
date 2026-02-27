<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Illuminate\Support\Collection;

class SeatCollectionData extends Data
{
    public function __construct(public array $seats) {}

    public static function fromCollection(Collection $seats): self
    {
        $formatted = $seats
            ->groupBy('row')
            ->map(
                fn($rowSeats) => [
                    'row' => $rowSeats->first()->row,
                    'seats' => $rowSeats->sortBy('number')->values()->toArray(),
                ],
            )
            ->values()
            ->toArray();

        return new self($formatted);
    }
}
