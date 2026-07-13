<?php

namespace App\Services;

use App\Enums\SportType;
use App\Models\BowlingScore;
use App\Models\Participant;
use App\Models\Sport;
use Illuminate\Support\Collection;

class DashboardService
{
    public function __construct(
        private readonly ScoreboardService $scoreboardService,
        private readonly BowlingService $bowlingService,
    ) {}

    public function summary(): array
    {
        $sports = Sport::query()
            ->withCount([
                'matches',
                'matches as completed_matches_count' => fn ($query) => $query
                    ->whereHas('result', fn ($result) => $result->whereNotNull('winner_house_id')),
            ])
            ->orderBy('name')
            ->get();
        $totalMatches = $sports->sum('matches_count');
        $completedMatches = $sports->sum('completed_matches_count');
        $totalBowlingScores = BowlingScore::query()->count();
        $houseRankings = $this->scoreboardService->houseStandings()->take(4)->values();

        return [
            'totalParticipants' => Participant::query()->count(),
            'totalEvents' => $sports->count(),
            'participantsWithoutEvents' => Participant::query()
                ->doesntHave('sports')
                ->count(),
            'totalMatches' => $totalMatches,
            'completedMatches' => $completedMatches,
            'pendingMatches' => $totalMatches - $completedMatches,
            'totalBowlingScores' => $totalBowlingScores,
            'matchProgress' => $totalMatches > 0 ? (int) round(($completedMatches / $totalMatches) * 100) : 0,
            'houseRankings' => $houseRankings,
            'leader' => $houseRankings->first(),
            'eventProgress' => $this->eventProgress($sports, $totalBowlingScores),
        ];
    }

    private function eventProgress(Collection $sports, int $bowlingScores): Collection
    {
        $bowlingPlayers = Participant::query()
            ->whereHas('sports', fn ($query) => $query->where('type', SportType::Bowling))
            ->count();

        return $sports->map(function (Sport $sport) use ($bowlingScores, $bowlingPlayers): array {
            if ($sport->type === SportType::Bowling) {
                $total = $bowlingPlayers * 2;
                $completed = min($bowlingScores, $total);

                return [
                    'sport' => $sport,
                    'completed' => $completed,
                    'total' => $total,
                    'percentage' => $total > 0 ? (int) round(($completed / $total) * 100) : 0,
                    'complete' => $this->bowlingService->isComplete(),
                ];
            }

            return [
                'sport' => $sport,
                'completed' => $sport->completed_matches_count,
                'total' => $sport->matches_count,
                'percentage' => $sport->matches_count > 0
                    ? (int) round(($sport->completed_matches_count / $sport->matches_count) * 100)
                    : 0,
                'complete' => $sport->matches_count > 0
                    && $sport->completed_matches_count === $sport->matches_count,
            ];
        });
    }
}
