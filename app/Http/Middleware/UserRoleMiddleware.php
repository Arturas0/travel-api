<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user()->roles()->where('name', $role)->exists()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
