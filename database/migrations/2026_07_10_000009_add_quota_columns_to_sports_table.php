<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sports', function (Blueprint $table): void {
            $table->unsignedTinyInteger('male_quota')->default(0)->after('type');
            $table->unsignedTinyInteger('female_quota')->default(0)->after('male_quota');
        });
    }

    public function down(): void
    {
        Schema::table('sports', function (Blueprint $table): void {
            $table->dropColumn(['male_quota', 'female_quota']);
        });
    }
};