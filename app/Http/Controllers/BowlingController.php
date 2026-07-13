<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use App\Services\BowlingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BowlingController extends Controller
{
    public function __construct(private readonly BowlingService $bowlingService) {}

    public function index(): View
    {
        return view('bowling.index', [
            'playerTotals' => $this->bowlingService->playerTotals(),
            'houseTotals' => $this->bowlingService->houseTotals(),
            'isComplete' => $this->bowlingService->isComplete(),
            'sports' => Sport::query()->where('type', 'bowling')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'sport_id' => ['required', 'exists:sports,id'],
            'participant_id' => ['required', 'exists:participants,id'],
            'game_1' => ['nullable', 'required_without:game_2', 'integer', 'min:0'],
            'game_2' => ['nullable', 'required_without:game_1', 'integer', 'min:0'],
        ]);

        $this->bowlingService->saveGames(
            (int) $data['sport_id'],
            (int) $data['participant_id'],
            isset($data['game_1']) ? (int) $data['game_1'] : null,
            isset($data['game_2']) ? (int) $data['game_2'] : null,
        );

        return redirect()->route('bowling.index')->with('success', 'Skor boling berjaya dikemas kini.');
    }

    public function reset(): RedirectResponse
    {
        $deleted = $this->bowlingService->resetAll();

        return back()->with('success', "{$deleted} skor boling telah direset.");
    }
}
