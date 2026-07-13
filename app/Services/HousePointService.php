<?php

namespace App\Services;

use App\Enums\MatchStage;
use App\Enums\SportType;
use App\Models\BowlingScore;
use App\Models\House;
use App\Models\LeagueMatch;
use App\Models\PointSetting;
use Illuminate\Support\Collection;

class HousePointService
{
    private const DEFAULT_POSITION_POINTS = [1 => 10, 2 => 7, 3 => 5, 4 => 3];

    public function __construct(private readonly BowlingService $bowlingService) {}

    public function pointsByHouse(): Collection
    {
        $points = House::query()->pluck('id')->mapWithKeys(fn (int $id) => [$id => 0]);

        foreach ($this->leaguePointsByHouse() as $houseId => $value) {
            $points[$houseId] += $value;
        }

        foreach ($this->bowlingPointsByHouse() as $houseId => $value) {
            $points[$houseId] += $value;
        }

        return $points;
    }

    public function leaguePointsByHouse(): array
    {
        $points = [];
        $settings = $this->positionPoints();
        $tournaments = LeagueMatch::query()
            ->with(['result', 'sport'])
            ->whereHas('sport', fn ($query) => $query->where('type', SportType::League))
            ->whereIn('stage', [MatchStage::Final, MatchStage::ThirdPlace])
            ->whereHas('result', fn ($query) => $query->whereNotNull('winner_house_id'))
            ->get()
            ->groupBy(fn (LeagueMatch $match) => $match->sport_id.'-'.$match->gender->value);

        foreach ($tournaments as $matches) {
            $final = $matches->firstWhere('stage', MatchStage::Final);
            $thirdPlace = $matches->firstWhere('stage', MatchStage::ThirdPlace);

            if (! $final || ! $thirdPlace) {
                continue;
            }

            $placements = [
                1 => $final->result->winner_house_id,
                2 => $this->loserId($final),
                3 => $thirdPlace->result->winner_house_id,
                4 => $this->loserId($thirdPlace),
            ];

            foreach ($placements as $position => $houseId) {
                $points[$houseId] = ($points[$houseId] ?? 0) + (int) ($settings[$position] ?? 0);
            }
        }

        return $points;
    }

    public function bowlingPointsByHouse(): array
    {
        if (! BowlingScore::query()->exists() || ! $this->bowlingService->isComplete()) {
            return [];
        }

        $totals = $this->bowlingService->houseTotals();

        if ($totals->isEmpty()) {
            return [];
        }

        $settings = $this->positionPoints();
        $points = [];
        $previousTotal = null;
        $position = 0;

        foreach ($totals->sortDesc() as $houseId => $total) {
            $index = count($points) + 1;

            if ($previousTotal === null || $total < $previousTotal) {
                $position = $index;
            }

            $points[$houseId] = (int) ($settings[$position] ?? 0);
            $previousTotal = $total;
        }

        return $points;
    }

    private function loserId(LeagueMatch $match): int
    {
        return $match->result->winner_house_id === $match->home_house_id
            ? $match->away_house_id
            : $match->home_house_id;
    }

    public function positionPoints(): Collection
    {
        return collect(self::DEFAULT_POSITION_POINTS)->replace(
            PointSetting::query()
                ->whereIn('position', array_keys(self::DEFAULT_POSITION_POINTS))
                ->pluck('points', 'position')
                ->map(fn ($points) => (int) $points),
        );
    }
}
