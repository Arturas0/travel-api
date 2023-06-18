<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourRequest;
use App\Http\Requests\UpsertTourRequest;
use App\Http\Resources\v1\TourResource;
use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TourController extends Controller
{
    public function index(Travel $travel, TourRequest $request): AnonymousResourceCollection
    {
        return TourResource::collection(
            $travel->tours()
                ->searchByPriceFrom($request->input('priceFrom'))
                ->searchByPriceTo($request->input('priceTo'))
                ->searchByDateFrom($request->input('dateFrom'))
                ->searchByDateTo($request->input('dateTo'))
                ->sortByPrice($request->input('sortByPrice'))
                ->sortByStartDateAsc()
                ->paginate()
        );
    }

    public function store(
        UpsertTourRequest $request,
        Travel $travel,
    ): TourResource {
        return TourResource::make(
            $travel->tours()->create($request->validated())
        );
    }
}
