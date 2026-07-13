<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Http\Requests\Dart\StoreDartResultRequest;
use App\Models\LeagueMatch;
use App\Models\Sport;
use App\Services\DartService;
use App\Services\KnockoutBracketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DartController extends Controller
{
    public function __construct(
        private readonly KnockoutBracketService $bracketService,
        private readonly DartService $dartService,
    ) {}

    public function index(Request $request): View
    {
        $sport = Sport::query()->where('name', 'Dart')->firstOrFail();
        $gender = Gender::tryFrom((string) $request->input('gender')) ?? Gender::Male;

        return view('dart.index', [
            'sport' => $sport,
            'gender' => $gender,
            ...$this->bracketService->for($sport, $gender),
        ]);
    }

    public function store(StoreDartResultRequest $request, LeagueMatch $match): RedirectResponse
    {
        $data = $request->validated();
        $this->dartService->submitWinner($match, (int) $data['winner_house_id']);

        return back()->with('success', 'Keputusan Dart berjaya disimpan.');
    }
}
