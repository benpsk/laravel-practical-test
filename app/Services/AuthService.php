<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthService
{

    /**
     * @param $credentials
     * @return JsonResponse
     */
    public function login($credentials): JsonResponse
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return response()->json([
                'user' => $user,
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    /**
     * @param $data
     * @return User
     */
    public function store($data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }
}
