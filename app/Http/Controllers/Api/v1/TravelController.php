<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpsertTravelRequest;
use App\Http\Resources\v1\TravelResource;
use App\Models\Travel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Travels
 */
class TravelController extends Controller
{
    /**
     * Get travels list
     * @unauthenticated
     */
    public function index(): AnonymousResourceCollection
    {
        return TravelResource::collection(
            Travel::query()
                ->where('is_public', '=', true)
                ->paginate()
        );
    }

    /**
     * Create travel
     * @authenticated
     */
    public function store(UpsertTravelRequest $request): TravelResource
    {
        return TravelResource::make(
            Travel::create([
                ...$request->validated(),
            ])
        );
    }

    /**
     * Update travel
     * @authenticated
     */
    public function update(UpsertTravelRequest $request, Travel $travel): TravelResource
    {
        $travel->update($request->validated());

        return TravelResource::make(
            $travel->refresh()
        );
    }
}
