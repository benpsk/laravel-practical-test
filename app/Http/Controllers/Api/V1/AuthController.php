<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        return $this->service->login($credentials);
    }

    /**
     * @param StoreUserRequest $request
     * @return mixed
     */
    public function store(StoreUserRequest $request): mixed
    {
        $data = $request->only('name', 'email', 'password');

        return $this->service->store($data);
    }
}
