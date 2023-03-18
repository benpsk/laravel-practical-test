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
        // Get a random user ID from the existing user records
        $userIds = User::pluck('id')->toArray();
        $userId = $this->faker->randomElement($userIds);

        return [
            'name' => fake()->name(),
            'phone_no' => fake()->phoneNumber(),
            'dob' => fake()->date(),
            'gender' => rand(0, 1) == 1 ? 'Male' : 'Female',
            'user_id' => $userId,
        ];
    }
}
