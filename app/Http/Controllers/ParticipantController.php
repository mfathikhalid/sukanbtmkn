<?php

namespace App\Http\Controllers;

use App\Http\Requests\Participants\StoreParticipantRequest;
use App\Http\Requests\Participants\UpdateParticipantRequest;
use App\Models\House;
use App\Models\Participant;
use App\Services\ParticipantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ParticipantController extends Controller
{
    public function __construct(private readonly ParticipantService $participantService)
    {
    }

    public function index(): View
    {
        return view('participants.index', [
            'participants' => $this->participantService->paginate(request()->only('search', 'house_id', 'gender')),
            'houses' => House::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('participants.create', [
            'participant' => new Participant(),
            'houses' => House::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreParticipantRequest $request): RedirectResponse
    {
        $this->participantService->create($request->validated());

        return redirect()->route('participants.index')->with('success', 'Participant created successfully.');
    }

    public function edit(Participant $participant): View
    {
        return view('participants.edit', [
            'participant' => $participant,
            'houses' => House::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateParticipantRequest $request, Participant $participant): RedirectResponse
    {
        $this->participantService->update($participant, $request->validated());

        return redirect()->route('participants.index')->with('success', 'Participant updated successfully.');
    }

    public function destroy(Participant $participant): RedirectResponse
    {
        $this->participantService->delete($participant);

        return redirect()->route('participants.index')->with('success', 'Participant deleted successfully.');
    }
}