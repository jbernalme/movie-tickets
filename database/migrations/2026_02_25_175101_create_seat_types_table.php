<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seat_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique(); // 'regular', 'vip', 'luxury'
            $table->string('display_name', 100); // 'Asiento Regular', 'VIP Premium'
            $table->text('description')->nullable();
            $table->decimal('price_multiplier', 5, 2)->default(1.0); // ← MULTIPLICADOR
            $table->string('color_class', 50)->default('bg-green-500');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_types');
    }
};
