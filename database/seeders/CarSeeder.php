<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cars')->insert([
            [
            'id' => 1,
            'car_category_id' => 1,
            'user_id' => 1001,
            'regist_date' => '2009-06-26',
            'inspect_date' => '2026-06-25',
            'car_info' => 'Alfa Romeo MiTo is an Italian luxury car manufacturer, known for its sporty vehicles and rich motorsport heritage.',
            'car_image' => '',
            'sort_order' => 1,
            ],
            [
            'id' => 2,
            'car_category_id' => 7,
            'user_id' => 1001,
            'regist_date' => '2023-01-01',
            'inspect_date' => '2024-01-01',
            'car_info' => 'Alfa Romeo MiTo is an Italian luxury car manufacturer, known for its sporty vehicles and rich motorsport heritage.',
            'car_image' => '',
            'sort_order' => 1,
            ],


    ]);
    }
}
