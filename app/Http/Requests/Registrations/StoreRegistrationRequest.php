<?php

namespace App\Http\Requests\Registrations;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sport_id' => ['required', 'exists:sports,id'],
            'participant_id' => ['required', 'exists:participants,id'],
        ];
    }
}