<?php

namespace App\Http\Controllers;

use App\Http\Requests\Public\StorePublicRegistrationRequest;
use App\Models\House;
use App\Models\Participant;
use App\Models\Sport;
use App\Services\PublicRegistrationService;
use App\Services\ScoreboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PublicRegistrationController extends Controller
{
    public function __construct(
        private readonly PublicRegistrationService $registrationService,
        private readonly ScoreboardService $scoreboardService,
    ) {}

    public function create(): View
    {
        return view('public.registration', [
            'houses' => House::query()->orderBy('name')->get(),
            'participants' => Participant::query()->with('sports:id')->orderBy('name')->get(),
            'sports' => Sport::query()->get()
                ->sortBy(fn (Sport $sport) => $this->scoreboardService->eventOrder($sport->name))
                ->values(),
            'registrationOpensAt' => $this->registrationService->opensAt(),
            'registrationIsOpen' => $this->registrationService->isOpen(),
        ]);
    }

    public function store(StorePublicRegistrationRequest $request): RedirectResponse
    {
        $participant = $this->registrationService->register($request->validated());

        return redirect()->route('public-registration.create')->with(
            'success',
            "Pendaftaran {$participant->name} berjaya diterima.",
        );
    }
}
