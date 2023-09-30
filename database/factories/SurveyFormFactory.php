<?php

namespace Database\Factories;

use App\Models\SurveyForm;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SurveyForm>
 */
class SurveyFormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone_no' => fake()->phoneNumber(),
            'dob' => fake()->date(),
            'gender' => rand(0, 1) == 1 ? 'Male' : 'Female',
            'user_id' => User::factory()
        ];
    }
}
