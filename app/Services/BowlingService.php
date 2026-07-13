<?php

namespace App\Services;

use App\Models\BowlingScore;
use App\Models\Participant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BowlingService
{
    public function listing(): Collection
    {
        return BowlingScore::query()
            ->with(['sport', 'participant.house'])
            ->orderBy('participant_id')
            ->orderBy('game_number')
            ->get();
    }

    public function save(int $sportId, int $participantId, int $gameNumber, int $score): BowlingScore
    {
        if (! in_array($gameNumber, [1, 2], true)) {
            throw ValidationException::withMessages(['game_number' => 'Nombor game mesti 1 atau 2.']);
        }

        $registered = Participant::query()
            ->whereKey($participantId)
            ->whereHas('sports', fn ($query) => $query->whereKey($sportId)->where('type', 'bowling'))
            ->exists();

        if (! $registered) {
            throw ValidationException::withMessages([
                'participant_id' => 'Peserta mesti berdaftar untuk acara boling ini.',
            ]);
        }

        return BowlingScore::query()->updateOrCreate(
            [
                'sport_id' => $sportId,
                'participant_id' => $participantId,
                'game_number' => $gameNumber,
            ],
            ['score' => $score]
        );
    }

    public function delete(BowlingScore $score): void
    {
        $score->delete();
    }

    public function resetAll(): int
    {
        return BowlingScore::query()->delete();
    }

    public function saveGames(
        int $sportId,
        int $participantId,
        ?int $gameOneScore,
        ?int $gameTwoScore,
    ): void {
        DB::transaction(function () use ($sportId, $participantId, $gameOneScore, $gameTwoScore): void {
            if ($gameOneScore !== null) {
                $this->save($sportId, $participantId, 1, $gameOneScore);
            }

            if ($gameTwoScore !== null) {
                $this->save($sportId, $participantId, 2, $gameTwoScore);
            }
        });
    }

    public function houseTotals(): Collection
    {
        return Participant::query()
            ->with(['house', 'bowlingScores.sport'])
            ->get()
            ->groupBy('house_id')
            ->map(fn (Collection $participants) => $participants->sum(fn (Participant $participant) => $participant->bowlingScores->sum('score')))
            ->sortDesc();
    }

    public function playerTotals(): Collection
    {
        return Participant::query()
            ->with(['house', 'bowlingScores'])
            ->whereHas('sports', fn ($query) => $query->where('type', 'bowling'))
            ->orderBy('name')
            ->get()
            ->map(fn (Participant $participant) => [
                'participant' => $participant,
                'game_1' => $participant->bowlingScores->firstWhere('game_number', 1)?->score,
                'game_2' => $participant->bowlingScores->firstWhere('game_number', 2)?->score,
                'total' => $participant->bowlingScores->sum('score'),
            ]);
    }

    public function isComplete(): bool
    {
        return $this->status() === 'complete';
    }

    public function status(): string
    {
        $registrations = Participant::query()
            ->whereHas('sports', fn ($query) => $query->where('type', 'bowling'))
            ->withCount(['bowlingScores as bowling_games_count'])
            ->get();

        if ($registrations->isEmpty() || $registrations->sum('bowling_games_count') === 0) {
            return 'not_started';
        }

        if ($registrations->every(fn (Participant $participant) => $participant->bowling_games_count === 2)) {
            return 'complete';
        }

        return 'ongoing';
    }
}
