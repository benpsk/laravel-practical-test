<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use SebastianBergmann\Type\VoidType;
use Tests\TestCase;

use function PHPUnit\Framework\assertJson;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_login_fail_validation(): void
    {
        $data = [
            'email' => 'bengunn',
            'password' => 'dddddd'
        ];
        $response = $this->json('POST', 'api/v1/login', $data);

        $response->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "email" => [
                        "The email field must be a valid email address."
                    ],
                ]
            ]);
    }

    public function test_user_can_login(): void
    {
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
    }

    public function test_user_fail_register(): void
    {

        $data = [
            'name' => 'John Doe',
            'email' => 'bengunn',
            'password' => 'Password1',
            'password_confirmation' => 'Padkdkd'
        ];

        $this->json('POST', 'api/v1/register', $data)
            ->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "email" => [
                        "The email field must be a valid email address."
                    ],
                    "password" => [
                        "The password field confirmation does not match."
                    ],
                ]
            ]);
    }

    public function test_user_can_register(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'bengunn.dev@gmail.com',
            'password' => 'Password1',
            'password_confirmation' => 'Password1'
        ];

        $this->json('POST', 'api/v1/register', $data)
            ->assertStatus(201);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->json('POST', 'api/v1/logout')
            ->assertStatus(200)
            ->assertJson([
                "data" => ["message" => "logout successful."]
            ]);
    }
}
