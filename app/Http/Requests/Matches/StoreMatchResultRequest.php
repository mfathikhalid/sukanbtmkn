<?php

namespace App\Http\Requests\Matches;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatchResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'winner_house_id' => ['required', 'integer', 'exists:houses,id'],
        ];
    }
}
