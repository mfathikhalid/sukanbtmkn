<?php

namespace App\Services;

use App\Enums\Gender;
use App\Models\Participant;
use App\Models\Sport;
use App\Models\SportRegistration;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class RegistrationService
{
    public function listing(array $filters = []): Collection
    {
        return SportRegistration::query()
            ->with(['sport', 'participant.house'])
            ->when($filters['sport_id'] ?? null, fn ($query, $sportId) => $query->where('sport_id', $sportId))
            ->when($filters['house_id'] ?? null, fn ($query, $houseId) => $query->whereHas('participant', fn ($participantQuery) => $participantQuery->where('house_id', $houseId)))
            ->when($filters['gender'] ?? null, fn ($query, $gender) => $query->whereHas('participant', fn ($participantQuery) => $participantQuery->where('gender', $gender)))
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($nestedQuery) use ($search) {
                    $nestedQuery
                        ->whereHas('sport', fn ($sportQuery) => $sportQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('participant', fn ($participantQuery) => $participantQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->get();
    }

    public function create(int $sportId, int $participantId): SportRegistration
    {
        $sport = Sport::query()->findOrFail($sportId);
        $participant = Participant::query()->findOrFail($participantId);

        if (SportRegistration::query()->where('sport_id', $sport->id)->where('participant_id', $participant->id)->exists()) {
            throw ValidationException::withMessages([
                'participant_id' => 'This participant is already registered for the selected sport.',
            ]);
        }

        $this->ensureQuotaAvailable($sport, $participant);

        return SportRegistration::query()->create([
            'sport_id' => $sport->id,
            'participant_id' => $participant->id,
        ]);
    }

    public function delete(SportRegistration $registration): void
    {
        $registration->delete();
    }

    private function ensureQuotaAvailable(Sport $sport, Participant $participant): void
    {
        $quotaColumn = match ($participant->gender) {
            Gender::Male => 'male_quota',
            Gender::Female => 'female_quota',
        };

        $quota = (int) $sport->{$quotaColumn};

        if ($quota <= 0) {
            throw ValidationException::withMessages([
                'sport_id' => 'This sport does not accept '.$participant->gender->value.' participants.',
            ]);
        }

        $currentCount = SportRegistration::query()
            ->where('sport_id', $sport->id)
            ->whereHas('participant', fn ($query) => $query->where('gender', $participant->gender->value))
            ->count();

        if ($currentCount >= $quota) {
            throw ValidationException::withMessages([
                'sport_id' => 'Gender quota for this sport is already full.',
            ]);
        }
    }
}