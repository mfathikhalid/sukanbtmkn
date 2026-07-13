<?php

namespace App\Services;

use App\Models\LeagueMatch;
use App\Models\MatchResult;
use Illuminate\Validation\ValidationException;

class DartService
{
    public function __construct(private readonly MatchResultService $matchResultService) {}

    public function submitWinner(LeagueMatch $match, int $winnerHouseId): MatchResult
    {
        if ($match->sport()->value('name') !== 'Dart') {
            throw ValidationException::withMessages([
                'match' => 'Perlawanan ini bukan acara Dart.',
            ]);
        }

        if (! in_array($winnerHouseId, [$match->home_house_id, $match->away_house_id], true)) {
            throw ValidationException::withMessages([
                'winner_house_id' => 'Pemenang mesti salah satu rumah dalam perlawanan ini.',
            ]);
        }

        return $this->matchResultService->submitWinner($match, $winnerHouseId);
    }
}
