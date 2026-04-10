<?php

namespace Database\Seeders;

use App\Models\Screening;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ScreeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Screening::count() === 0) {
            Screening::create([
                'movie_id' => 438631,
                'hall_id' => 1,
                'start_time' => Carbon::create(2026, 10, 22, 19, 0, 0),
                'end_time' => Carbon::create(2026, 10, 22, 21, 30, 0),
                'base_price' => 10.0,
                'format' => '2D',
                'audio' => 'dubbed',
                'status' => 'upcoming',
            ]);
            Screening::create([
                'movie_id' => 2,
                'hall_id' => 1,
                'start_time' => Carbon::create(2026, 10, 25, 19, 0, 0),
                'end_time' => Carbon::create(2026, 10, 25, 21, 30, 0),
                'base_price' => 10.0,
                'format' => '2D',
                'audio' => 'dubbed',
                'status' => 'upcoming',
            ]);
            Screening::create([
                'movie_id' => 2,
                'hall_id' => 1,
                'start_time' => Carbon::create(2026, 10, 25, 22, 0, 0),
                'end_time' => Carbon::create(2026, 10, 32, 24, 30, 0),
                'base_price' => 10.0,
                'format' => '2D',
                'audio' => 'dubbed',
                'status' => 'upcoming',
            ]);
            Screening::create([
                'movie_id' => 2,
                'hall_id' => 1,
                'start_time' => Carbon::create(2026, 11, 5, 19, 0, 0),
                'end_time' => Carbon::create(2026, 11, 5, 21, 30, 0),
                'base_price' => 10.0,
                'format' => '2D',
                'audio' => 'dubbed',
                'status' => 'upcoming',
            ]);
            Screening::create([
                'movie_id' => 2,
                'hall_id' => 1,
                'start_time' => Carbon::create(2026, 11, 5, 20, 0, 0),
                'end_time' => Carbon::create(2026, 11, 5, 22, 30, 0),
                'base_price' => 10.0,
                'format' => '2D',
                'audio' => 'subtitles',
                'status' => 'upcoming',
            ]);
            Screening::create([
                'movie_id' => 2,
                'hall_id' => 1,
                'start_time' => Carbon::create(2026, 11, 5, 19, 0, 0),
                'end_time' => Carbon::create(2026, 11, 5, 21, 30, 0),
                'base_price' => 10.0,
                'format' => '2D',
                'audio' => 'subtitles',
                'status' => 'upcoming',
            ]);
        }
    }
}
