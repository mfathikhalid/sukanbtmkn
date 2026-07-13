<?php

namespace App\Services;

use App\Models\House;
use App\Models\LeagueMatch;
use App\Models\Participant;
use App\Models\Sport;

class DashboardService
{
    public function summary(): array
    {
        $houses = House::query()->orderBy('name')->get();
        $sports = Sport::query()->orderBy('name')->get();

        return [
            'houses' => $houses,
            'sports' => $sports,
            'totalParticipants' => Participant::query()->count(),
            'totalEvents' => $sports->count(),
            'participantsWithoutEvents' => Participant::query()
                ->doesntHave('sports')
                ->count(),
            'totalMatches' => LeagueMatch::query()->count(),
            'completedMatches' => LeagueMatch::query()
                ->whereHas('result', fn ($query) => $query->whereNotNull('winner_house_id'))
                ->count(),
            'pendingMatches' => LeagueMatch::query()
                ->whereDoesntHave('result', fn ($query) => $query->whereNotNull('winner_house_id'))
                ->count(),
            'houseRankings' => $this->houseRankings(),
        ];
    }

    private function houseRankings(): array
    {
        return app(ScoreboardService::class)->houseStandings()->take(4)->all();
    }
}
