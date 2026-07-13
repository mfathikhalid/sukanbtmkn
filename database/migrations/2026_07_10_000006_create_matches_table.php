<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_id')->constrained('sports')->cascadeOnDelete();
            $table->foreignId('home_house_id')->constrained('houses')->restrictOnDelete();
            $table->foreignId('away_house_id')->constrained('houses')->restrictOnDelete();
            $table->foreignId('winner_house_id')->nullable()->constrained('houses')->restrictOnDelete();
            $table->timestamp('played_at')->nullable();
            $table->timestamps();

            $table->unique(['sport_id', 'home_house_id', 'away_house_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};