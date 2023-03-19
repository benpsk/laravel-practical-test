<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Services\AuthService;
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
     */
    public function login(LoginUserRequest $request)
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

    public function logout(Request $request)
    {
        $user = request()->user();
        // Revoke all tokens...
        $user->tokens()->delete();

        $response = ['message' => 'logout successful.'];
        return $this->service->formatter()
        ->make($response, 200);
    }
}
