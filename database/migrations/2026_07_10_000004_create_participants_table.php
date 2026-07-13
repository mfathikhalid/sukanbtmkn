<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('house_id')->constrained('houses')->restrictOnDelete();
            $table->string('employee_no')->unique();
            $table->string('name');
            $table->enum('gender', ['Male', 'Female']);
            $table->string('department')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};