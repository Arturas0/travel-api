<?php

declare(strict_types=1);

use App\Http\Controllers\Api\v1\TourController;
use App\Http\Controllers\Api\v1\TravelController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TravelController::class, 'index'])->name('index');
Route::get('{travel}/tours', [TourController::class, 'index'])->name('tours');
