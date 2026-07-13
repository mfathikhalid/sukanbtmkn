<?php

namespace App\Services;

use App\Models\Participant;
use Illuminate\Support\Facades\DB;

class PublicRegistrationService
{
    public function __construct(
        private readonly RegistrationService $registrationService,
    ) {}

    public function register(array $data): Participant
    {
        return DB::transaction(function () use ($data): Participant {
            $participant = Participant::query()->findOrFail($data['participant_id']);

            foreach ($data['sport_ids'] as $sportId) {
                $this->registrationService->create((int) $sportId, $participant->id);
            }

            return $participant;
        });
    }
}
