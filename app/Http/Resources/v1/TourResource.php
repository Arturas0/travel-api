<?php

declare(strict_types=1);

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'tours',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'price' => $this->price,
            ]
        ];
    }
}
