<?php

declare(strict_types=1);

use App\Http\Controllers\Api\v1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');
