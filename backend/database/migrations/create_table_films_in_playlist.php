<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('films_in_playlist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('playlist_id');
            $table->integer('id_kp');
            $table->string('poster_url');
            $table->string('name');
            $table->string('rating');
            $table->boolean('is_serial');
            $table->integer('season')->nullable()->default(null);
            $table->integer('episode')->nullable()->default(null);
            $table->string('studio')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films_in_playlist');
    }
};
