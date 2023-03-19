<?php

namespace App\Services;

use App\Api\Exceptions\WrongCredentialException;
use App\Api\Service\CommonService;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthService extends CommonService
{

    /**
     * @param $credentials
     * @return JsonResponse
     * @throws WrongCredentialException
     */
    public function login($credentials): JsonResponse
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $token = $user->createToken('auth-token')->plainTextToken;

            return $this->formatter()
                ->authResponse(
                    $token,
                    new UserResource($user)
                );
        }

        throw new WrongCredentialException('Invalid credentials', 401);

    }

    /**
     * @param $data
     * @return JsonResponse
     */
    public function store($data): JsonResponse
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return $this->formatter()
        ->make(new UserResource($user), 201);

    }
}
