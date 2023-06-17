<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('travels')->as('travels.')->group(
    base_path('routes/v1/travels.php'),
);

Route::prefix('auth')->as('auth.')->group(
    base_path('routes/v1/auth.php'),
);
