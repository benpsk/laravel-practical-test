<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('login fail validation', function () {
    $data = [
        'email' => 'bengunn',
        'password' => 'dddddd'
    ];

    $this->json('POST', 'api/v1/login', $data);

    expect(
        '{
            "success": 0,
            "status": 422,
            "meta": {
                "method": "post",
                "endpoint": "api/v1/login"
            },
            "errors": {
                "email": [
                    "The email field must be a valid email address."
                ]
            }
        }'
    )->toBeJson();
});

test('user can login', function () {
    $user = User::factory()->create([
        'password' => 'Password1',
    ]);
    $data = [
        'email' => $user->email,
        'password' => 'Password1'
    ];
    $this->json('POST', 'api/v1/login', $data)
        ->assertStatus(200);

    $this->assertAuthenticatedAs($user);
});

test('user fail register', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'bengunn',
        'password' => 'Password1',
        'password_confirmation' => 'Padkdkd'
    ];

    $this->json('POST', 'api/v1/register', $data)
        ->assertStatus(422);

    expect(
        [
            "errors" => [
                "email" => [
                    "The email field must be a valid email address."
                ],
                "password" => [
                    "The password field confirmation does not match."
                ],
            ]
        ]
    )->toBeArray();
});

test('user can register', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'bengunn.dev@gmail.com',
        'password' => 'Password1',
        'password_confirmation' => 'Password1'
    ];

    $this->json('POST', 'api/v1/register', $data)
        ->assertStatus(201);
});

test('user can logout', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $this->json('POST', 'api/v1/logout')
        ->assertStatus(200);

    expect(
        [
            "data" => ["message" => "logout successful."]
        ]
    )->toBeArray();
});
