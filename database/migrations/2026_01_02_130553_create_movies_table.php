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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('tmdb_id')->unique();
            $table->string('original_title');
            $table->text('overview');
            $table->string('slug')->unique();
            $table->string('poster_path');
            $table->string('genres');
            $table->date('release_date');
            $table->string('backdrop_path')->nullable();

            // Details
            $table->string('imdb_id')->nullable();
            $table->integer('runtime')->nullable();
            $table->string('tagline')->nullable();
            $table->string('status')->nullable();

            $table->timestamp('last_synced_at')->nullable();
            $table->boolean('needs_detail_sync')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
