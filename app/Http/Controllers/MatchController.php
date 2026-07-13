<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Http\Requests\Matches\StoreMatchResultRequest;
use App\Models\LeagueMatch;
use App\Models\Sport;
use App\Services\KnockoutService;
use App\Services\LeagueFixtureService;
use App\Services\MatchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function __construct(
        private readonly MatchService $matchService,
        private readonly KnockoutService $knockoutService,
        private readonly LeagueFixtureService $fixtureService,
    ) {}

    public function update(StoreMatchResultRequest $request, LeagueMatch $match): RedirectResponse
    {
        $data = $request->validated();

        $this->matchService->recordWinner($match, (int) $data['winner_house_id']);

        return back()->with('success', 'Match result saved successfully.');
    }

    public function semiFinals(Request $request, Sport $sport): RedirectResponse
    {
        $created = $this->knockoutService->generateSemiFinals($sport, $this->gender($request));

        return back()->with('success', "Generated {$created} semi-final matches.");
    }

    public function leagueFixtures(Request $request, Sport $sport): RedirectResponse
    {
        $created = $this->fixtureService->generate($sport, $this->gender($request));

        return back()->with('success', "Generated {$created} round-robin matches.");
    }

    public function finals(Request $request, Sport $sport): RedirectResponse
    {
        $created = $this->knockoutService->generateFinal($sport, $this->gender($request));

        return back()->with('success', "Generated {$created} final match.");
    }

    public function thirdPlace(Request $request, Sport $sport): RedirectResponse
    {
        $created = $this->knockoutService->generateThirdPlace($sport, $this->gender($request));

        return back()->with('success', "Generated {$created} third-place match.");
    }

    private function gender(Request $request): Gender
    {
        return Gender::tryFrom((string) $request->input('gender')) ?? Gender::Male;
    }
}
