<?php

namespace Tests\Feature;

use App\Models\SurveyForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SurveyFormTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }


    public function test_can_get_survey_form_listing(): void
    {
        SurveyForm::factory(10)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->json('GET', 'api/v1/survey')
            ->assertStatus(200);

        $response->assertJsonCount(10, 'data.survey_form');
    }

    public function test_user_fail_store_survey(): void
    {
        $user = [
            'name' => 'John Doe',
            'dob' => '2023-01-10'
        ];

        $this->json('POST', 'api/v1/survey', $user)
            ->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "phone_no" => [
                        "The phone no field is required."
                    ]
                ]
            ]);
    }

    public function test_user_can_store_survey(): void
    {
        $user = [
            'name' => 'John Doe',
            'phone_no' => '+959453340064',
            'gender' => 'Male',
            'dob' => '2023-01-10'
        ];

        $this->json('POST', 'api/v1/survey', $user)
            ->assertStatus(201);
    }
}
