<?php

namespace App\Services;

use App\Enums\Gender;
use App\Models\LeagueMatch;
use App\Models\MatchResult;
use App\Models\Sport;
use Illuminate\Support\Collection;

class MatchService
{
    public function __construct(
        private readonly LeagueFixtureService $fixtureService,
        private readonly MatchResultService $resultService,
    ) {}

    public function listing(): Collection
    {
        return LeagueMatch::query()
            ->with(['sport', 'homeHouse', 'awayHouse', 'result.winnerHouse'])
            ->orderBy('sport_id')
            ->orderBy('gender')
            ->orderBy('stage')
            ->orderBy('match_no')
            ->get();
    }

    public function generateFixtures(): int
    {
        $sports = Sport::query()->where('type', 'league')->orderBy('name')->get();
        $created = 0;

        foreach ($sports as $sport) {
            foreach ([Gender::Male, Gender::Female] as $gender) {
                $created += $this->fixtureService->generate($sport, $gender);
            }
        }

        return $created;
    }

    public function recordWinner(LeagueMatch $match, int $winnerHouseId): MatchResult
    {
        return $this->resultService->submitWinner($match, $winnerHouseId);
    }

    public function delete(LeagueMatch $match): void
    {
        $match->delete();
    }
}
