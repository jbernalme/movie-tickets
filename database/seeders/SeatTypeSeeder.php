<?php

namespace Database\Seeders;

use App\Models\SeatType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SeatTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SeatType::create([
            'id' => 1,
            'name' => 'regular',
            'display_name' => 'Asiento Regular',
            'price_multiplier' => 1.0,
            'color_class' => 'bg-green-500', // ← Clase de Tailwind
        ]);

        SeatType::create([
            'id' => 2,
            'name' => 'vip',
            'display_name' => 'VIP Premium',
            'price_multiplier' => 1.5,
            'color_class' => 'bg-yellow-500',
        ]);

        SeatType::create([
            'id' => 3,
            'name' => 'luxury',
            'display_name' => 'Luxury Recliner',
            'price_multiplier' => 2.0,
            'color_class' => 'bg-purple-500',
        ]);
    }
}
