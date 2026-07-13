<?php

namespace App\Services;

use App\Enums\Gender;
use App\Enums\MatchStage;
use App\Models\LeagueMatch;
use App\Models\Sport;

class KnockoutBracketService
{
    public function for(Sport $sport, Gender $gender): array
    {
        $matches = LeagueMatch::query()
            ->with(['homeHouse', 'awayHouse', 'result.winnerHouse'])
            ->whereBelongsTo($sport)
            ->where('gender', $gender)
            ->whereIn('stage', [
                MatchStage::League,
                MatchStage::SemiFinal,
                MatchStage::ThirdPlace,
                MatchStage::Final,
            ])
            ->orderBy('match_no')
            ->get();

        return [
            'leagueMatches' => $matches->where('stage', MatchStage::League)->values(),
            'semiFinals' => $matches->where('stage', MatchStage::SemiFinal)->values(),
            'final' => $matches->firstWhere('stage', MatchStage::Final),
            'thirdPlace' => $matches->firstWhere('stage', MatchStage::ThirdPlace),
        ];
    }
}
