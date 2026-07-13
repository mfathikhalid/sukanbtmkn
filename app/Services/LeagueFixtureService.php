<?php

namespace App\Services;

use App\Enums\Gender;
use App\Enums\MatchStage;
use App\Enums\SportType;
use App\Models\House;
use App\Models\LeagueMatch;
use App\Models\Sport;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LeagueFixtureService
{
    private const FIXTURES = [
        ['Merah', 'Biru'],
        ['Hijau', 'Kuning'],
        ['Merah', 'Hijau'],
        ['Biru', 'Kuning'],
        ['Merah', 'Kuning'],
        ['Biru', 'Hijau'],
    ];

    public function generate(Sport $sport, Gender $gender = Gender::Male): int
    {
        if ($sport->type !== SportType::League) {
            throw ValidationException::withMessages([
                'sport' => 'Perlawanan hanya boleh dijana untuk sukan liga.',
            ]);
        }

        return DB::transaction(function () use ($sport, $gender): int {
            $houses = House::query()
                ->whereIn('name', collect(self::FIXTURES)->flatten()->unique())
                ->get()
                ->keyBy('name');

            if ($houses->count() !== 4) {
                throw ValidationException::withMessages([
                    'houses' => 'Empat rumah Merah, Biru, Hijau dan Kuning diperlukan.',
                ]);
            }

            $created = 0;

            foreach (self::FIXTURES as $index => [$homeName, $awayName]) {
                $match = LeagueMatch::query()->firstOrCreate([
                    'sport_id' => $sport->id,
                    'gender' => $gender,
                    'stage' => MatchStage::League,
                    'match_no' => $index + 1,
                ], [
                    'home_house_id' => $houses[$homeName]->id,
                    'away_house_id' => $houses[$awayName]->id,
                ]);

                $created += (int) $match->wasRecentlyCreated;
            }

            return $created;
        });
    }
}
