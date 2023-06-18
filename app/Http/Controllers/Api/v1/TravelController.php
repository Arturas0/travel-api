<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertTravelRequest;
use App\Http\Resources\v1\TravelResource;
use App\Models\Travel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TravelController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return TravelResource::collection(
            Travel::query()
                ->where('is_public', '=', true)
                ->paginate()
        );
    }

    public function store(UpsertTravelRequest $request): TravelResource
    {
        return TravelResource::make(
            Travel::create([
                ...$request->validated()
            ])
        );
    }

    public function update(UpsertTravelRequest $request, Travel $travel): TravelResource
    {
        $travel->update([
            ...$request->validated(),
        ]);

        return TravelResource::make(
            $travel->refresh()
        );
    }
}
