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
            'user_id' => fake()->numberBetween(16,97), // Create a new user for each order
            'car_id'  => fake()->numberBetween(5,92),
            'shop_id'  => fake()->numberBetween(1101,1102),
            'staff_id'  => fake()->numberBetween(9,11),
            'order_status' => 1,
            'order_info' => fake()->realText(50),
            'pitin_date' => fake()->dateTimeBetween('-3 month', '+1 month'), // Specify date range
        ];

    }
}
