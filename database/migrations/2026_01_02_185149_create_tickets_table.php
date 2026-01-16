<?php

use App\Models\Discount;
use App\Models\User;
use App\Models\Screening;
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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Screening::class);
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Discount::class);
            $table->string('ticket_code', 20)->unique();
            $table->decimal('total_price', 10, 2);
            $table->decimal('discount_price', 10, 2);
            $table->decimal('final_price', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'used'])->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('used_at')->nullable();

            $table->index('ticket_code');
            $table->index('status');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
