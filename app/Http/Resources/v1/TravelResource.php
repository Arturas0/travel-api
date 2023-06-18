<?php

declare(strict_types=1);

namespace App\Http\Resources\v1;

use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Travel
 */
class TravelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'travels',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'number_of_days' => $this->number_of_days,
                'number_of_nights' => $this->number_of_nights,
            ],
            'relationships' => [
                'tours' => [
                    TourResource::collection($this->whenLoaded('tours')),
                ],
            ],
        ];
    }
}
