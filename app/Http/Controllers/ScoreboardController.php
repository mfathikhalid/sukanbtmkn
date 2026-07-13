<?php

namespace App\Http\Controllers;

use App\Services\ScoreboardService;
use Illuminate\View\View;

class ScoreboardController extends Controller
{
    public function __construct(private readonly ScoreboardService $scoreboardService) {}

    public function index(): View
    {
        return view('scoreboard.index', [
            'standings' => $this->scoreboardService->houseStandings(),
            'eventBreakdown' => $this->scoreboardService->eventBreakdown(),
        ]);
    }
}
