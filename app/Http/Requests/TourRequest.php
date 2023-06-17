<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TourRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'priceFrom' => [
                'sometimes',
                'integer',
                'min:0',
            ],
            'priceTo' => [
                'sometimes',
                'integer',
                'min:0',
            ],
            'dateFrom' => [
                'sometimes',
                'date',
                'after_or_equal:today',
            ],
            'dateTo' => [
                'sometimes',
                'date',
                'after_or_equal:today',
            ],
            'sortByPrice' => [
                'sometimes',
                Rule::in(['ASC', 'DESC']),
            ]
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }
}
