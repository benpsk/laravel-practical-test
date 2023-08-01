<?php

use App\Models\SurveyForm;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    Sanctum::actingAs($this->user);
});

test('can get survey form listing', function () {
    SurveyForm::factory(10)->create([
        'user_id' => $this->user->id
    ]);

    $response = $this->json('GET', 'api/v1/survey')
        ->assertStatus(200);

    $response->assertJsonCount(10, 'data.survey_form');
});

test('user fail store survey', function () {
    $user = [
        'name' => 'John Doe',
        'dob' => '2023-01-10'
    ];

    expect([
        "errors" => [
            "phone_no" => [
                "The phone no field is required."
            ]
        ]
    ])->toBeJson();
});

test('user can store survey', function () {
    $user = [
        'name' => 'John Doe',
        'phone_no' => '+959453340064',
        'gender' => 'Male',
        'dob' => '2023-01-10'
    ];

    $this->json('POST', 'api/v1/survey', $user)
        ->assertStatus(201);
});
