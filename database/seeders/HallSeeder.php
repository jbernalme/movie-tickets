<?php

namespace Database\Seeders;

use App\Models\Hall;
use App\Models\Seat;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HallSeeder extends Seeder
{
    public function run(): void
    {
        $this->createCinemaHall();
        $this->createTheaterHall();
        $this->createVIPHall();
    }

    private function createCinemaHall(): void
    {
        $hall = Hall::create([
            'name' => 'Sala Cinema 1',
            'capacity' => 150,
            'rows' => 10,
            'seats_per_row' => 16, // 7 + pasillo + 7
            'status' => 'active',
        ]);

        $seats = [];

        for ($row = 1; $row <= $hall->rows; $row++) {
            $rowLetter = chr(64 + $row); // A, B, C...

            for ($col = 1; $col <= 16; $col++) {
                // Pasillo central en columna 8
                if ($col == 8) {
                    $seats[] = [
                        'hall_id' => $hall->id,
                        'row' => $rowLetter,
                        'number' => 'W' . $col, // Walkway
                        'seat_type_id' => 1,
                        'status' => 'unavailable',
                        'grid_x' => $col,
                        'grid_y' => $row,
                        'is_walkway' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    continue;
                }

                // Ajustar número de asiento (sin contar pasillo)
                $seatNumber = $col > 8 ? $col - 1 : $col;

                $seats[] = [
                    'hall_id' => $hall->id,
                    'row' => $rowLetter,
                    'number' => (string) $seatNumber,
                    'seat_type_id' => $this->getCinemaType($row, $col),
                    'status' => 'available',
                    'grid_x' => $col,
                    'grid_y' => $row,
                    'is_walkway' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Seat::insert($seats);
    }

    private function createTheaterHall(): void
    {
        $hall = Hall::create([
            'name' => 'Teatro Principal',
            'capacity' => 200,
            'rows' => 15,
            'seats_per_row' => 20,
            'status' => 'active',
        ]);

        $seats = [];

        for ($row = 1; $row <= $hall->rows; $row++) {
            for ($col = 1; $col <= 20; $col++) {
                // Doble pasillo (columnas 7 y 14)
                $isWalkway = in_array($col, [7, 14]);

                $seats[] = [
                    'hall_id' => $hall->id,
                    'row' => chr(64 + $row),
                    'number' => $isWalkway ? 'W' . $col : (string) $col,
                    'seat_type_id' => $this->getTheaterType($row),
                    'status' => $isWalkway ? 'unavailable' : 'available',
                    'grid_x' => $col,
                    'grid_y' => $row,
                    'is_walkway' => $isWalkway,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Seat::insert($seats);
    }

    private function createVIPHall(): void
    {
        $hall = Hall::create([
            'name' => 'Sala VIP',
            'capacity' => 40,
            'rows' => 5,
            'seats_per_row' => 8,
            'status' => 'active',
        ]);

        $seats = [];

        for ($row = 1; $row <= $hall->rows; $row++) {
            for ($col = 1; $col <= 8; $col++) {
                $seats[] = [
                    'hall_id' => $hall->id,
                    'row' => chr(64 + $row),
                    'number' => (string) $col,
                    'seat_type_id' => 3, // Todos luxury
                    'status' => 'available',
                    'grid_x' => $col,
                    'grid_y' => $row,
                    'is_walkway' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Seat::insert($seats);
    }

    private function getCinemaType(int $row, int $col): int
    {
        // Asientos centrales de las últimas 3 filas son VIP
        if ($row >= 8 && $col >= 5 && $col <= 12) {
            return 2;
        }

        // Primera fila es luxury
        if ($row == 1) {
            return 3;
        }

        return 1;
    }

    private function getTheaterType(int $row): int
    {
        return match (true) {
            $row <= 3 => 3,
            $row <= 8 => 2,
            default => 1,
        };
    }
}
