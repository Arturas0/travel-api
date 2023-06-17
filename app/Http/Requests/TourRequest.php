<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

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

    public function messages(): array
    {
        return [
            'sortByPrice' => 'Valid options are "ASC" or "DESC"',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
