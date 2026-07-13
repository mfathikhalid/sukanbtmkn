<?php

namespace App\Services;

use App\Models\LeagueMatch;
use App\Models\MatchResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MatchResultService
{
    public function submitWinner(LeagueMatch $match, int $winnerHouseId): MatchResult
    {
        if (! in_array($winnerHouseId, [$match->home_house_id, $match->away_house_id], true)) {
            throw ValidationException::withMessages([
                'winner_house_id' => 'Pemenang mesti salah satu rumah dalam perlawanan ini.',
            ]);
        }

        return $winnerHouseId === $match->home_house_id
            ? $this->submit($match, 1, 0)
            : $this->submit($match, 0, 1);
    }

    public function submit(LeagueMatch $match, int $homeScore, int $awayScore): MatchResult
    {
        if ($homeScore < 0 || $awayScore < 0) {
            throw ValidationException::withMessages(['scores' => 'Skor tidak boleh negatif.']);
        }

        if ($match->sport()->value('name') === 'Dart') {
            if (! in_array([$homeScore, $awayScore], [[1, 0], [0, 1]], true)) {
                throw ValidationException::withMessages([
                    'scores' => 'Dart menggunakan satu permainan 501. Pilih satu rumah sebagai pemenang.',
                ]);
            }
        }

        return DB::transaction(function () use ($match, $homeScore, $awayScore): MatchResult {
            $match = LeagueMatch::query()->lockForUpdate()->findOrFail($match->id);

            $existingResult = $match->result()->first();

            if ($existingResult?->winner_house_id) {
                throw ValidationException::withMessages([
                    'result' => 'Keputusan perlawanan ini telah direkodkan.',
                ]);
            }

            if ($homeScore === $awayScore) {
                throw ValidationException::withMessages([
                    'scores' => 'Keputusan seri tidak dibenarkan. Perlawanan mesti mempunyai pemenang.',
                ]);
            }

            $winnerHouseId = $homeScore > $awayScore
                ? $match->home_house_id
                : $match->away_house_id;

            $result = $match->result()->updateOrCreate(['match_id' => $match->id], [
                'home_score' => $homeScore,
                'away_score' => $awayScore,
                'winner_house_id' => $winnerHouseId,
            ]);

            $match->update(['played_at' => now()]);

            return $result;
        });
    }
}
