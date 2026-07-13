<?php

use App\Enums\Gender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('matches', 'gender')) {
            Schema::table('matches', function (Blueprint $table): void {
                $table->enum('gender', [Gender::Male->value, Gender::Female->value])
                    ->default(Gender::Male->value)
                    ->after('sport_id');
            });
        }

        if (! $this->hasIndex('matches', 'matches_sport_id_index')) {
            Schema::table('matches', function (Blueprint $table): void {
                // Keep a dedicated index for the foreign key before dropping the old unique key.
                $table->index('sport_id', 'matches_sport_id_index');
            });
        }

        if ($this->hasIndex('matches', 'matches_sport_id_home_house_id_away_house_id_unique')) {
            Schema::table('matches', function (Blueprint $table): void {
                $table->dropUnique(['sport_id', 'home_house_id', 'away_house_id']);
            });
        }

        if (! $this->hasIndex('matches', 'matches_sport_id_gender_home_house_id_away_house_id_unique')) {
            Schema::table('matches', function (Blueprint $table): void {
                $table->unique(['sport_id', 'gender', 'home_house_id', 'away_house_id']);
            });
        }
    }

    public function down(): void
    {
        if ($this->hasIndex('matches', 'matches_sport_id_gender_home_house_id_away_house_id_unique')) {
            Schema::table('matches', function (Blueprint $table): void {
                $table->dropUnique(['sport_id', 'gender', 'home_house_id', 'away_house_id']);
            });
        }

        if (! $this->hasIndex('matches', 'matches_sport_id_home_house_id_away_house_id_unique')) {
            Schema::table('matches', function (Blueprint $table): void {
                $table->unique(['sport_id', 'home_house_id', 'away_house_id']);
            });
        }

        if ($this->hasIndex('matches', 'matches_sport_id_index')) {
            Schema::table('matches', function (Blueprint $table): void {
                $table->dropIndex('matches_sport_id_index');
            });
        }

        if (Schema::hasColumn('matches', 'gender')) {
            Schema::table('matches', function (Blueprint $table): void {
                $table->dropColumn('gender');
            });
        }
    }

    private function hasIndex(string $tableName, string $indexName): bool
    {
        return collect(Schema::getIndexes($tableName))
            ->contains(fn (array $index) => $index['name'] === $indexName);
    }
};
