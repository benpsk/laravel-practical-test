<?php

namespace App\Http\Controllers\Api\V1;

use App\Api\Exceptions\WrongCredentialException;
use App\Api\Service\Formatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @param AuthService $service
     */
    public function __construct(
        protected AuthService $service
    ) {
    }

    /**
     * @param LoginUserRequest $request
     * @return JsonResponse
     * @throws WrongCredentialException
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $user =  $this->service->login($credentials);
        if (!empty($user->token)) {
            Formatter::factory()->setToken(token: $user->token);
        }
        return response()->json(new UserResource($user));
    }

    /**
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->only('name', 'email', 'password');
        $user =  $this->service->store($data);
        return response()->json(new UserResource($user), 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        // Revoke all tokens...
        $user->tokens()->delete();

        $response = ['message' => 'logout successful.'];
        return response()->json($response);
    }
}
