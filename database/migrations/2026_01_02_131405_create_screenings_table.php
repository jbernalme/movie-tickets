<?php

use App\Models\Hall;
use App\Models\Movie;
use App\Models\Theater;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('screenings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Movie::class);
            $table->foreignIdFor(Hall::class);
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->enum('format', ['2D', '3D', 'IMAX'])->default('2D');
            $table->enum('audio', ['subtitles', 'dubbed'])->default('subtitles');
            $table->enum('status', ['upcoming', 'ongoing', 'finished'])->default('upcoming');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screenings');
    }
};
