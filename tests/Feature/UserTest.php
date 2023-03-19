<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_unauthenticate(): void
    {
        $this->json('GET', 'api/v1/user')
            ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => "Unauthenticated."
                ]
            ]);
    }

    public function test_user_can_authenticate(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->json('GET', 'api/v1/user')
            ->assertStatus(200);
    }
}
