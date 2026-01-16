<?php

use App\Models\Seat;
use App\Models\Ticket;
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
        Schema::create('seat_ticket', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Seat::class);
            $table->foreignIdFor(Ticket::class);
            $table->decimal('price', 10, 2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_ticket');
    }
};
