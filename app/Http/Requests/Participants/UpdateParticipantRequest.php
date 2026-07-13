<?php

namespace App\Http\Requests\Participants;

use App\Enums\Gender;
use App\Models\Participant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Participant|null $participant */
        $participant = $this->route('participant');

        return [
            'house_id' => ['required', 'exists:houses,id'],
            'employee_no' => ['required', 'string', 'max:255', Rule::unique('participants', 'employee_no')->ignore($participant?->id)],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'department' => ['nullable', 'string', 'max:255'],
        ];
    }
}