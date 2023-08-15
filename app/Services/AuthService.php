<?php

namespace App\Services;

use App\Api\Exceptions\WrongCredentialException;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class AuthService
{

    /**
     * @throws WrongCredentialException
     */
    public function login($credentials): ?Authenticatable
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $token = $user->createToken('auth-token')->plainTextToken;
            $user->token = $token;
            return $user;
        }

        throw new WrongCredentialException('Invalid credentials', 401);

    }

    public function store($data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }
}
