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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('first_club_id');
            $table->unsignedBigInteger('second_club_id');
            $table->integer('score_first_club');
            $table->integer('score_second_club');
            $table->timestamps();
            // Relations
            $table->foreign('first_club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->foreign('second_club_id')->references('id')->on('clubs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
