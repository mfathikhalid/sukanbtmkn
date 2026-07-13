<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sport_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_id')->constrained('sports')->cascadeOnDelete();
            $table->foreignId('participant_id')->constrained('participants')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['sport_id', 'participant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sport_registrations');
    }
};