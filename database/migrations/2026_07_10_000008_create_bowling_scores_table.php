<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bowling_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_id')->constrained('sports')->cascadeOnDelete();
            $table->foreignId('participant_id')->constrained('participants')->cascadeOnDelete();
            $table->unsignedSmallInteger('score');
            $table->timestamps();

            $table->unique(['sport_id', 'participant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bowling_scores');
    }
};