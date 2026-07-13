<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('position')->unique();
            $table->unsignedSmallInteger('points');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_settings');
    }
};