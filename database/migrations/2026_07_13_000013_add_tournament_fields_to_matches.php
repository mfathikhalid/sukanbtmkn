<?php

use App\Enums\MatchStage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table): void {
            $table->string('stage')->default(MatchStage::League->value)->after('gender');
            $table->unsignedTinyInteger('match_no')->nullable()->after('stage');
        });

        $groups = DB::table('matches')
            ->select(['sport_id', 'gender'])
            ->distinct()
            ->get();

        foreach ($groups as $group) {
            DB::table('matches')
                ->where('sport_id', $group->sport_id)
                ->where('gender', $group->gender)
                ->orderBy('id')
                ->get(['id'])
                ->each(fn ($match, int $index) => DB::table('matches')
                    ->where('id', $match->id)
                    ->update(['match_no' => $index + 1]));
        }

        Schema::table('match_results', function (Blueprint $table): void {
            $table->foreignId('winner_house_id')
                ->nullable()
                ->after('away_score')
                ->constrained('houses')
                ->restrictOnDelete();
        });

        DB::table('matches')
            ->whereNotNull('winner_house_id')
            ->orderBy('id')
            ->get()
            ->each(function ($match): void {
                DB::table('match_results')
                    ->where('match_id', $match->id)
                    ->update(['winner_house_id' => $match->winner_house_id]);
            });

        Schema::table('matches', function (Blueprint $table): void {
            $table->dropUnique('matches_sport_id_gender_home_house_id_away_house_id_unique');
            $table->dropConstrainedForeignId('winner_house_id');
            $table->unique(
                ['sport_id', 'gender', 'stage', 'match_no'],
                'matches_tournament_slot_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table): void {
            $table->foreignId('winner_house_id')
                ->nullable()
                ->after('away_house_id')
                ->constrained('houses')
                ->restrictOnDelete();
            $table->dropUnique('matches_tournament_slot_unique');
            $table->unique(
                ['sport_id', 'gender', 'home_house_id', 'away_house_id'],
                'matches_sport_id_gender_home_house_id_away_house_id_unique'
            );
            $table->dropColumn(['stage', 'match_no']);
        });

        Schema::table('match_results', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('winner_house_id');
        });
    }
};
