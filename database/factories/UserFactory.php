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
    public function definition(): array
    {
        $tel = str_replace('-', '', fake()->phoneNumber());
        $address = mb_substr(fake()->address(), 9); // Limit address length to 50 characters
        return [
            'name' => fake()->name(),
            'kana' => fake()->KanaName(), // Japanese name in Kana
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'user_info' => fake()->realText(50), // Default user info
            'role_id'  => 99, // Default role ID for regular users
            'shop_id'  => fake()->numberBetween(1101,1102),  // Default shop ID
            'postcode' => fake()->postcode(),
            'address' => $address,
            'tel' => $tel,
            'mailService' => fake()->numberBetween(0,1), // Default mail service enabled
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
