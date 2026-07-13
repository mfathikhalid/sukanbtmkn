<?php

namespace App\Services;

use App\Enums\Gender;
use App\Enums\MatchStage;
use App\Models\House;
use App\Models\LeagueMatch;
use App\Models\Sport;
use Illuminate\Support\Collection;

class LeagueStandingService
{
    public function calculate(Sport $sport, Gender $gender = Gender::Male): Collection
    {
        $standings = House::query()
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (House $house) => [$house->id => $this->emptyRow($house)])
            ->all();

        $matches = LeagueMatch::query()
            ->with('result')
            ->whereBelongsTo($sport)
            ->where('gender', $gender)
            ->where('stage', MatchStage::League)
            ->whereHas('result', fn ($query) => $query->whereNotNull('winner_house_id'))
            ->get();

        foreach ($matches as $match) {
            $result = $match->result;
            $home = &$standings[$match->home_house_id];
            $away = &$standings[$match->away_house_id];

            $home['played']++;
            $away['played']++;
            $home['goals_for'] += $result->home_score;
            $home['goals_against'] += $result->away_score;
            $away['goals_for'] += $result->away_score;
            $away['goals_against'] += $result->home_score;

            if ($result->home_score === $result->away_score) {
                $home['draw']++;
                $away['draw']++;
            } elseif ($result->home_score > $result->away_score) {
                $home['won']++;
                $away['lost']++;
                $home['points']++;
            } else {
                $away['won']++;
                $home['lost']++;
                $away['points']++;
            }

            unset($home, $away);
        }

        return collect($standings)
            ->map(function (array $row): array {
                $row['goal_difference'] = $row['goals_for'] - $row['goals_against'];

                return $row;
            })
            ->sort(fn (array $left, array $right) => [
                $right['points'],
                $right['goal_difference'],
                $right['goals_for'],
                $left['house']->name,
            ] <=> [
                $left['points'],
                $left['goal_difference'],
                $left['goals_for'],
                $right['house']->name,
            ])
            ->values();
    }

    private function emptyRow(House $house): array
    {
        return [
            'house' => $house,
            'played' => 0,
            'won' => 0,
            'draw' => 0,
            'lost' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goal_difference' => 0,
            'points' => 0,
        ];
    }
}
