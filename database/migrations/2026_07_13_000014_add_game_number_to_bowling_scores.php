<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bowling_scores', function (Blueprint $table): void {
            $table->index('sport_id', 'bowling_scores_sport_id_index');
            $table->index('participant_id', 'bowling_scores_participant_id_index');
        });

        Schema::table('bowling_scores', function (Blueprint $table): void {
            $table->dropUnique('bowling_scores_sport_id_participant_id_unique');
            $table->unsignedTinyInteger('game_number')->default(1)->after('participant_id');
            $table->unique(
                ['sport_id', 'participant_id', 'game_number'],
                'bowling_scores_player_game_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('bowling_scores', function (Blueprint $table): void {
            $table->dropUnique('bowling_scores_player_game_unique');
            $table->dropColumn('game_number');
            $table->unique(['sport_id', 'participant_id']);
            $table->dropIndex('bowling_scores_sport_id_index');
            $table->dropIndex('bowling_scores_participant_id_index');
        });
    }
};
