<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JsonException;
use Random\RandomException;

/**
 * @extends Factory<\App\Models\User>
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
     * @throws RandomException
     * @throws JsonException
     */
    public function definition(): array
    {
        $countryJsonFile = storage_path('app/data/countries.json');
        $countriesArray = json_decode(file_get_contents($countryJsonFile), true, 512, JSON_THROW_ON_ERROR);
        $countryNames = array_column($countriesArray, 'name');
        return [
            'name' => fake()->name(),
            'surname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'country' => $countryNames[random_int(0, count($countryNames)-1)],
            'gender' => fake()->randomElement(['male', 'female']),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'selfie' => fake()->imageUrl(),
            'introduction' => fake()->text(200),
            'remember_token' => Str::random(10),
        ];
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
