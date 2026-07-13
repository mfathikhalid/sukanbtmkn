<?php

namespace App\Http\Controllers;

use App\Http\Requests\Registrations\StoreRegistrationRequest;
use App\Models\Participant;
use App\Models\Sport;
use App\Models\SportRegistration;
use App\Services\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function __construct(private readonly RegistrationService $registrationService)
    {
    }

    public function index(Request $request): View
    {
        return view('registrations.index', [
            'registrations' => $this->registrationService->listing([
                'search' => $request->input('search'),
                'sport_id' => $request->input('sport_id'),
                'house_id' => $request->input('house_id'),
                'gender' => $request->input('gender'),
            ]),
            'sports' => Sport::query()->orderBy('name')->get(),
            'houses' => \App\Models\House::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('registrations.create', [
            'sports' => Sport::query()->orderBy('name')->get(),
            'houses' => \App\Models\House::query()->orderBy('name')->get(),
            'participants' => Participant::query()->orderBy('name')->get(),
            'registration' => new SportRegistration(),
        ]);
    }

    public function store(StoreRegistrationRequest $request): RedirectResponse
    {
        $this->registrationService->create(
            (int) $request->validated('sport_id'),
            (int) $request->validated('participant_id'),
        );

        return redirect()->route('registrations.index')->with('success', 'Registration saved successfully.');
    }

    public function destroy(SportRegistration $registration): RedirectResponse
    {
        $this->registrationService->delete($registration);

        return redirect()->route('registrations.index')->with('success', 'Registration deleted successfully.');
    }
}