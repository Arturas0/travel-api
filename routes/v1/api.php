<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

include __DIR__.'/auth.php';

Route::prefix('travels')->as('travels.')->group(
    base_path('routes/v1/travels.php'),
);
