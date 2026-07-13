<?php

namespace App\Http\Requests\Participants;

use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'house_id' => ['required', 'exists:houses,id'],
            'employee_no' => ['required', 'string', 'max:255', 'unique:participants,employee_no'],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'department' => ['nullable', 'string', 'max:255'],
        ];
    }
}