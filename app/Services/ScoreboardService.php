<?php

namespace App\Services;

use App\Enums\Gender;
use App\Enums\MatchStage;
use App\Enums\SportType;
use App\Models\House;
use App\Models\LeagueMatch;
use App\Models\Sport;
use Illuminate\Support\Collection;

class ScoreboardService
{
    private const EVENT_ORDER = [
        'Congkak' => 1,
        'FIFA' => 2,
        'Tekken' => 3,
        'Dart' => 4,
        'Carrom' => 5,
        'Bowling' => 6,
        'Pickleball' => 7,
    ];

    public function __construct(
        private readonly HousePointService $housePointService,
        private readonly BowlingService $bowlingService,
    ) {}

    public function houseStandings(): Collection
    {
        $houses = House::query()->orderBy('name')->get()->keyBy('id');
        $points = $this->housePointService->pointsByHouse();
        $leaguePoints = $this->housePointService->leaguePointsByHouse();
        $bowlingPoints = $this->housePointService->bowlingPointsByHouse();
        $bowlingTotals = $this->bowlingService->houseTotals();
        $wins = $this->winsByHouse();

        return $houses->map(fn (House $house) => [
            'house' => $house,
            'points' => $points[$house->id] ?? 0,
            'round_robin_wins' => $wins[$house->id]['round_robin'] ?? 0,
            'knockout_wins' => $wins[$house->id]['knockout'] ?? 0,
            'event_points' => $leaguePoints[$house->id] ?? 0,
            'bowling_points' => $bowlingPoints[$house->id] ?? 0,
            'bowling_total' => $bowlingTotals[$house->id] ?? 0,
        ])->sort(function (array $left, array $right): int {
            return [$right['points'], $right['round_robin_wins'], $right['knockout_wins'], $right['bowling_total']]
                <=> [$left['points'], $left['round_robin_wins'], $left['knockout_wins'], $left['bowling_total']];
        })->values();
    }

    public function houseEventPoints(): Collection
    {
        return $this->housePointService->pointsByHouse();
    }

    public function eventBreakdown(): Collection
    {
        $houses = House::query()->orderBy('name')->get();
        $settings = $this->housePointService->positionPoints();
        $matches = LeagueMatch::query()
            ->with('result')
            ->whereIn('stage', [MatchStage::Final, MatchStage::ThirdPlace])
            ->get();

        $events = Sport::query()
            ->where('type', SportType::League)
            ->orderBy('name')
            ->get()
            ->flatMap(function (Sport $sport) use ($houses, $matches, $settings): array {
                return collect([
                    Gender::Male->value => $sport->male_quota,
                    Gender::Female->value => $sport->female_quota,
                ])->filter(fn (int $quota) => $quota > 0)
                    ->keys()
                    ->map(function (string $gender) use ($sport, $houses, $matches, $settings): array {
                        $eventMatches = $matches
                            ->where('sport_id', $sport->id)
                            ->where('gender.value', $gender);
                        $final = $eventMatches->firstWhere('stage', MatchStage::Final);
                        $thirdPlace = $eventMatches->firstWhere('stage', MatchStage::ThirdPlace);
                        $complete = (bool) ($final?->result?->winner_house_id && $thirdPlace?->result?->winner_house_id);
                        $points = $houses->mapWithKeys(fn (House $house) => [$house->id => 0]);

                        if ($complete) {
                            $placements = [
                                1 => $final->result->winner_house_id,
                                2 => $this->loserId($final),
                                3 => $thirdPlace->result->winner_house_id,
                                4 => $this->loserId($thirdPlace),
                            ];

                            foreach ($placements as $position => $houseId) {
                                $points[$houseId] = (int) ($settings[$position] ?? 0);
                            }
                        }

                        return [
                            'event' => $sport->name,
                            'category' => $gender === Gender::Male->value ? 'Lelaki' : 'Perempuan',
                            'complete' => $complete,
                            'points' => $points,
                        ];
                    })->all();
            });

        $bowlingSport = Sport::query()->where('type', SportType::Bowling)->first();

        if ($bowlingSport) {
            $bowlingPoints = $this->housePointService->bowlingPointsByHouse();
            $events->push([
                'event' => $bowlingSport->name,
                'category' => 'Keseluruhan',
                'complete' => $this->bowlingService->isComplete(),
                'points' => $houses->mapWithKeys(fn (House $house) => [
                    $house->id => $bowlingPoints[$house->id] ?? 0,
                ]),
            ]);
        }

        return $events
            ->sortBy(fn (array $event) => [
                self::EVENT_ORDER[$event['event']] ?? PHP_INT_MAX,
                $event['category'] === 'Lelaki' ? 1 : 2,
            ])
            ->values();
    }

    private function winsByHouse(): array
    {
        return LeagueMatch::query()
            ->with('result')
            ->whereHas('sport', fn ($query) => $query->where('type', SportType::League))
            ->whereHas('result', fn ($query) => $query->whereNotNull('winner_house_id'))
            ->get()
            ->groupBy(fn (LeagueMatch $match) => $match->result->winner_house_id)
            ->map(fn (Collection $matches) => [
                'round_robin' => $matches->where('stage', MatchStage::League)->count(),
                'knockout' => $matches->where('stage', '!=', MatchStage::League)->count(),
            ])
            ->all();
    }

    private function loserId(LeagueMatch $match): int
    {
        return $match->result->winner_house_id === $match->home_house_id
            ? $match->away_house_id
            : $match->home_house_id;
    }
}
