<?php
namespace App\Data;

use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\Validation\{
    Required,
    IntegerType,
    Exists,
    ArrayType,
    Min,
    Max,
    StringType,
    Max as MaxLength,
};
use Spatie\LaravelData\Data;

class TicketReservationData extends Data
{
    public function __construct(
        #[
            Required,
            IntegerType,
            Exists('users', 'id'),
        ]
        public readonly int $userId,

        #[
            Required,
            IntegerType,
            Exists('screenings', 'id'),
        ]
        public readonly int $screeningId,

        #[Required, ArrayType, Min(1), Max(10)] #[
            Exists('seats', 'id'),
        ]
        public readonly array $seatIds,

        #[StringType, MaxLength(20)] public readonly ?string $discountCode,
    ) {}

    // Factory method desde Request
    public static function fromRequest(array $data, int $userId): self
    {
        return new self(
            userId: $userId,
            screeningId: $data['screening_id'],
            seatIds: $data['seat_ids'],
            discountCode: $data['discount_code'] ?? null,
        );
    }
}
