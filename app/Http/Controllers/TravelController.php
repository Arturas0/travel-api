<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\TravelResource;
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
}
