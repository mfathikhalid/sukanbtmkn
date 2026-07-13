<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\SportType;
use App\Models\Sport;
use App\Services\BowlingService;
use App\Services\KnockoutBracketService;
use App\Services\LeagueStandingService;
use App\Services\ScoreboardService;
use Illuminate\View\View;

class PublicLiveController extends Controller
{
    public function __construct(
        private readonly ScoreboardService $scoreboardService,
        private readonly KnockoutBracketService $bracketService,
        private readonly LeagueStandingService $standingService,
        private readonly BowlingService $bowlingService,
    ) {}

    public function __invoke(): View
    {
        $events = Sport::query()
            ->where('type', SportType::League)
            ->orderBy('name')
            ->get()
            ->flatMap(function (Sport $sport): array {
                return collect([
                    Gender::Male->value => $sport->male_quota,
                    Gender::Female->value => $sport->female_quota,
                ])->filter(fn (int $quota) => $quota > 0)
                    ->keys()
                    ->map(function (string $gender) use ($sport): array {
                        $genderEnum = Gender::from($gender);

                        return [
                            'type' => 'tournament',
                            'sport' => $sport,
                            'gender' => $genderEnum,
                            'category' => $genderEnum === Gender::Male ? 'Lelaki' : 'Perempuan',
                            'standings' => $this->standingService->calculate($sport, $genderEnum),
                            ...$this->bracketService->for($sport, $genderEnum),
                        ];
                    })->all();
            });

        $bowlingSport = Sport::query()->where('type', SportType::Bowling)->first();

        if ($bowlingSport) {
            $events->push([
                'type' => 'bowling',
                'sport' => $bowlingSport,
                'category' => 'Keseluruhan',
                'playerTotals' => $this->bowlingService->playerTotals()->sortByDesc('total')->values(),
                'houseTotals' => $this->bowlingService->houseTotals(),
                'complete' => $this->bowlingService->isComplete(),
            ]);
        }

        $events = $events
            ->sortBy(fn (array $event) => [
                $this->scoreboardService->eventOrder($event['sport']->name),
                ($event['gender'] ?? null) === Gender::Male ? 1 : 2,
            ])
            ->values();

        return view('public.live', [
            'standings' => $this->scoreboardService->houseStandings(),
            'eventBreakdown' => $this->scoreboardService->eventBreakdown(),
            'events' => $events,
        ]);
    }
}
