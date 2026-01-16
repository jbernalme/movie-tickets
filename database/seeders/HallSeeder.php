<?php

namespace Database\Seeders;

use App\Models\Hall;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Hall::count() === 0) {
            Hall::create([
                'name' => 'Sala 1',
                'capacity' => 100,
                'rows' => 10,
                'seats_per_row' => 10,
                'status' => 'active',
            ]);
        }
    }
}
