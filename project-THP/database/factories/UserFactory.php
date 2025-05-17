<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
 public function definition()
     {
         return [
             // تعديل العمود من name إلى full_name
             'full_name' => $this->faker->name,
             'username' => $this->faker->userName,
             'email' => $this->faker->unique()->safeEmail,
             'password' => Hash::make('password123'),
             'user_type' => $this->faker->randomElement(['job_owner', 'artisan']),
         ];
     }
 }
    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
