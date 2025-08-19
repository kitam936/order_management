<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => fake()->numberBetween(101, 200), // Unique ID for each order
            'item_id' => fake()->numberBetween(10300001, 10300004),
            'item_price'  => fake()->numberBetween(4000, 150000),
            'item_pcs'  => fake()->numberBetween(1, 4),
            'work_fee'  => fake()->numberBetween(3000, 300000),
            'detail_status' => 1,
            'detail_info' => fake()->realText(30),
            'workingtime' => fake()->numberBetween(1, 20),
        ];
    }
}
