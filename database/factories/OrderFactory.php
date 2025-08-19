<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->numberBetween(101, 200), // Unique ID for each order
            'user_id' => 1001, // Create a new user for each order
            'car_id'  => fake()->randomElement([1, 2]), // Generate car_id as either 1 or 7
            'shop_id'  => 1101,
            'staff_id'  => 2,
            'order_status' => 1,
            'order_info' => fake()->realText(50),
            'pitin_date' => fake()->dateTimeBetween('-5 years', '+1 month'), // Specify date range
        ];

    }
}
