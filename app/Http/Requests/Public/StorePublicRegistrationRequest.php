<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePublicRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'house_id' => ['required', 'exists:houses,id'],
            'participant_id' => [
                'required',
                Rule::exists('participants', 'id')->where(
                    fn ($query) => $query->where('house_id', $this->input('house_id'))
                ),
            ],
            'sport_ids' => ['required', 'array', 'min:1'],
            'sport_ids.*' => ['integer', 'distinct', 'exists:sports,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'house_id' => 'rumah',
            'participant_id' => 'nama peserta',
            'sport_ids' => 'acara',
            'sport_ids.*' => 'acara',
        ];
    }

    public function messages(): array
    {
        return [
            'sport_ids.required' => 'Sila pilih sekurang-kurangnya satu acara.',
            'sport_ids.min' => 'Sila pilih sekurang-kurangnya satu acara.',
            'participant_id.exists' => 'Peserta yang dipilih tidak sepadan dengan rumah.',
        ];
    }
}
