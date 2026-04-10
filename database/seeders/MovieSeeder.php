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
                'tmdb_id' => 438631,
                'title' => 'Dune',
                'original_title' => 'Dune',
                'overview' => 'Descripción de Dune',
                'poster_path' => 'poster_dune.jpg',
                'genres' => 'Ciencia ficción, Drama',
                'release_date' => '2021-10-22',
                'imdb_id' => 'tt1160419',
                'runtime' => 155,
                'tagline' => 'El destino de la humanidad está en juego',
                'status' => 'Released',
                'last_synced_at' => now(),
                'needs_detail_sync' => false,
            ]);
            Movie::create([
                'tmdb_id' => 1218925,
                'title' => 'Chainsaw man: Reze Arc',
                'original_title' => 'Chainsaw man: Reze Arc',
                'overview' => 'Descripción de Chainsaw man: Reze Arc',
                'poster_path' => 'poster_chainsaw_man.jpg',
                'genres' => 'Ciencia ficción, Drama',
                'release_date' => '2021-10-22',
                'imdb_id' => 'tt1160419',
                'runtime' => 125,
                'tagline' => '',
                'status' => 'Released',
                'last_synced_at' => now(),
                'needs_detail_sync' => false,
            ]);
        }
    }
}
