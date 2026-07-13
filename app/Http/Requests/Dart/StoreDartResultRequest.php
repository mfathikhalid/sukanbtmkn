<?php

namespace App\Http\Requests\Dart;

use Illuminate\Foundation\Http\FormRequest;

class StoreDartResultRequest extends FormRequest
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
