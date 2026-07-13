<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->unique()->constrained('matches')->cascadeOnDelete();
            $table->unsignedSmallInteger('home_score');
            $table->unsignedSmallInteger('away_score');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_results');
    }
};