<?php

namespace App\Services;

use App\Enums\Gender;
use App\Enums\MatchStage;
use App\Models\LeagueMatch;
use App\Models\Sport;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class KnockoutService
{
    public function __construct(private readonly LeagueStandingService $standingService) {}

    public function generateSemiFinals(Sport $sport, Gender $gender = Gender::Male): int
    {
        return DB::transaction(function () use ($sport, $gender): int {
            $leagueMatches = $this->matches($sport, $gender, MatchStage::League);

            if ($leagueMatches->count() !== 6 || $leagueMatches->contains(fn ($match) => ! $match->result?->winner_house_id)) {
                throw ValidationException::withMessages([
                    'matches' => 'Semua enam perlawanan liga mesti selesai terlebih dahulu.',
                ]);
            }

            $standings = $this->standingService->calculate($sport, $gender);
            $pairs = [
                [$standings[0]['house'], $standings[3]['house']],
                [$standings[1]['house'], $standings[2]['house']],
            ];

            return $this->createMatches($sport, $gender, MatchStage::SemiFinal, $pairs);
        });
    }

    public function generateFinal(Sport $sport, Gender $gender = Gender::Male): int
    {
        return DB::transaction(function () use ($sport, $gender): int {
            $semiFinals = $this->completedSemiFinals($sport, $gender);

            return $this->createMatches($sport, $gender, MatchStage::Final, [[
                $semiFinals[0]->result->winnerHouse,
                $semiFinals[1]->result->winnerHouse,
            ]]);
        });
    }

    public function generateThirdPlace(Sport $sport, Gender $gender = Gender::Male): int
    {
        return DB::transaction(function () use ($sport, $gender): int {
            $semiFinals = $this->completedSemiFinals($sport, $gender);

            return $this->createMatches($sport, $gender, MatchStage::ThirdPlace, [[
                $this->loser($semiFinals[0]),
                $this->loser($semiFinals[1]),
            ]]);
        });
    }

    private function completedSemiFinals(Sport $sport, Gender $gender)
    {
        $matches = $this->matches($sport, $gender, MatchStage::SemiFinal);

        if ($matches->count() !== 2 || $matches->contains(fn ($match) => ! $match->result?->winner_house_id)) {
            throw ValidationException::withMessages([
                'matches' => 'Kedua-dua perlawanan separuh akhir mesti selesai terlebih dahulu.',
            ]);
        }

        return $matches;
    }

    private function matches(Sport $sport, Gender $gender, MatchStage $stage)
    {
        return LeagueMatch::query()
            ->with(['result.winnerHouse', 'homeHouse', 'awayHouse'])
            ->whereBelongsTo($sport)
            ->where('gender', $gender)
            ->where('stage', $stage)
            ->orderBy('match_no')
            ->lockForUpdate()
            ->get();
    }

    private function createMatches(Sport $sport, Gender $gender, MatchStage $stage, array $pairs): int
    {
        $created = 0;

        foreach ($pairs as $index => [$home, $away]) {
            $match = LeagueMatch::query()->firstOrCreate([
                'sport_id' => $sport->id,
                'gender' => $gender,
                'stage' => $stage,
                'match_no' => $index + 1,
            ], [
                'home_house_id' => $home->id,
                'away_house_id' => $away->id,
            ]);

            $created += (int) $match->wasRecentlyCreated;
        }

        return $created;
    }

    private function loser(LeagueMatch $match)
    {
        return $match->result->winner_house_id === $match->home_house_id
            ? $match->awayHouse
            : $match->homeHouse;
    }
}
