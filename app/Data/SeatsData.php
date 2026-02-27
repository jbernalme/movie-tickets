<?php
namespace App\Data;

use Spatie\LaravelData\Data;

class SeatsData extends Data
{
    public function __construct(
        public int $id,
        public int $hall_id,
        public string $row,
        public int $number,
        public string $type,
        public string $status,
        public int $grid_x,
        public int $grid_y,
        public bool $is_walkway,
    ) {}
}
