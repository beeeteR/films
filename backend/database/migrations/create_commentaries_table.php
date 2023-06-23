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
        Schema::create('commentaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_film');
            $table->integer('id_user');
            $table->string('datetime');
            $table->boolean('is_answer')->default(false);
            $table->integer('answer_to_id')->default(null)->nullable();
            $table->longText('text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commentaries');
    }
};
