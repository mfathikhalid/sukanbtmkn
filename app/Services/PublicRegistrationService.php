<?php

namespace App\Services;

use App\Models\Participant;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PublicRegistrationService
{
    public function __construct(
        private readonly RegistrationService $registrationService,
    ) {}

    public function register(array $data): Participant
    {
        if (! $this->isOpen()) {
            throw ValidationException::withMessages([
                'sport_ids' => 'Pendaftaran acara dibuka mulai 14 Julai 2026.',
            ]);
        }

        return DB::transaction(function () use ($data): Participant {
            $participant = Participant::query()->findOrFail($data['participant_id']);

            foreach ($data['sport_ids'] as $sportId) {
                $this->registrationService->create((int) $sportId, $participant->id);
            }

            return $participant;
        });
    }

    public function opensAt(): CarbonImmutable
    {
        return CarbonImmutable::parse(
            config('carnival.registration_opens_at'),
            config('carnival.timezone'),
        );
    }

    public function isOpen(): bool
    {
        return CarbonImmutable::now(config('carnival.timezone'))->greaterThanOrEqualTo($this->opensAt());
    }
}
