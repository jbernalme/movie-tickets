<?php

use App\Models\Hall;
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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Hall::class);
            $table->string('row');
            $table->string('number');
            $table->enum('type', ['regular', 'vip', 'luxury'])->default('regular');
            $table->enum('status', ['available', 'unavailable'])->default('available');
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
