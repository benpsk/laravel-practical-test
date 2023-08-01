<?php

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('user unauthenticate', function () {
    expect([
        "errors" => [
            "message" => "Unauthenticated."
        ]
    ])->toBeJson();
});

test('user can authenticate', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $this->json('GET', 'api/v1/user')
        ->assertStatus(200);
});
