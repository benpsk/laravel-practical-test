<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array{name: string, email: string, password: array{0: string, 1: string, 2: Password}}
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:20',
            'email' => 'required|email|unique:users',
            'password' => [
                'required', 'confirmed',
                Password::min(6)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ]
        ];
    }
}
