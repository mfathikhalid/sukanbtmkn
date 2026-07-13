<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\SportType;
use App\Models\Sport;
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
                            'sport' => $sport,
                            'gender' => $genderEnum,
                            'category' => $genderEnum === Gender::Male ? 'Lelaki' : 'Perempuan',
                            'standings' => $this->standingService->calculate($sport, $genderEnum),
                            ...$this->bracketService->for($sport, $genderEnum),
                        ];
                    })->all();
            })
            ->values();

        return view('public.live', [
            'standings' => $this->scoreboardService->houseStandings(),
            'eventBreakdown' => $this->scoreboardService->eventBreakdown(),
            'events' => $events,
        ]);
    }
}
