<?php

namespace App\Services;

use App\Api\Exceptions\WrongCredentialException;
use App\Api\Service\CommonService;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService extends CommonService
{

    /**
     * @param $credentials
     */
    public function login($credentials)
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
     */
    public function store($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return $this->formatter()->make(new UserResource($user));

    }
}
