<?php

namespace App\Services;

use App\Api\Exceptions\WrongCredentialException;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class AuthService
{

    /**
     * @param array $credentials
     * @return Authenticatable|null
     * @throws WrongCredentialException
     */
    public function login(array $credentials): ?Authenticatable
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->tokens()->delete();
            $user->token = $user->createToken('auth-token')->plainTextToken;
            return $user;
        }

        throw new WrongCredentialException('Invalid credentials', 401);
    }

    public function store($data): User
    {
        return User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }
}
