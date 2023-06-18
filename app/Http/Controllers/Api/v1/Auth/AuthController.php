<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Auth
 * @unauthenticated
 */
class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @response {
     *  "token": "1Aytfsfy45kJDSDSD"
     * }
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (! auth()->attempt($request->validated())) {
            throw ValidationException::withMessages([
                'error' => ['The provided credentials are incorrect.'],
            ])
                ->status(Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'token' => auth()->user()->createToken($request->header('user_agent') ?? 'token')->plainTextToken,
        ]);
    }
}
