<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Movie::count() === 0) {
            Movie::create([
                'title' => 'Dune',
                'slug' => 'dune',
                'description' => 'Descripción de Dune',
                'poster' => 'poster_dune.jpg',
            ]);
            Movie::create([
                'title' => 'Chainsaw man: Reze Arc',
                'slug' => 'chainsaw-man-reze-arc',
                'description' => 'Descripción de Chainsaw man: Reze Arc',
                'poster' => 'poster_chainsaw_man.jpg',
            ]);
        }
    }
}
