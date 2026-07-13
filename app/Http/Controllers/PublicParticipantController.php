<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Sport;
use App\Services\ParticipantService;
use App\Services\ScoreboardService;
use Illuminate\View\View;

class PublicParticipantController extends Controller
{
    public function __construct(
        private readonly ParticipantService $participantService,
        private readonly ScoreboardService $scoreboardService,
    ) {}

    public function __invoke(): View
    {
        return view('public.participants', [
            'participants' => $this->participantService->publicListing(
                request()->only('search', 'house_id', 'gender', 'sport_id')
            ),
            'houses' => House::query()->orderBy('name')->get(),
            'sports' => Sport::query()->get()
                ->sortBy(fn (Sport $sport) => $this->scoreboardService->eventOrder($sport->name))
                ->values(),
        ]);
    }
}
