<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourRequest;
use App\Http\Resources\v1\TourResource;
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

    public function tours(Travel $travel, TourRequest $request)
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
}
