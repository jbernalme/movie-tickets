<?php

use App\Models\Hall;
use App\Models\SeatType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Hall::class);
            $table->foreignIdFor(SeatType::class);
            $table->string('row');
            $table->string('number');
            $table
                ->enum('status', ['available', 'unavailable'])
                ->default('available');

            $table->integer('grid_x')->comment('Posición en eje X (columna)');
            $table->integer('grid_y')->comment('Posición en eje Y (fila)');
            $table
                ->boolean('is_walkway')
                ->default(false)
                ->comment('Espacio vacío/pasillo');
            $table->timestamps();

            $table->unique(['hall_id', 'row', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
