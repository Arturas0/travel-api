<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertTravelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_public' => ['required', 'boolean'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'number_of_days' => ['required', 'int', 'min:1'],
        ];
    }
}
