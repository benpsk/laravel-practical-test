<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('user un-authenticate', function () {

    $this->json('GET', 'api/v1/user')
        ->assertStatus(401);

    expect([
        "errors" => [
            "message" => "Unauthenticated."
        ]
    ])->toBeArray();
});

test('user can authenticate', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $this->json('GET', 'api/v1/user')
        ->assertStatus(200);
});
