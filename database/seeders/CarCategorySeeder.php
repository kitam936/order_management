<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('car_categories')->insert([[
            'id' => 1,
            'makes_id' => 1,
            'car_name' => 'MiTo',
            'car_info' => 'Alfa Romeo is an Italian luxury car manufacturer, known for its sporty vehicles and rich motorsport heritage.',
            'sort_order' => '1',
        ],
        [
            'id' => 2,
            'makes_id' => 1,
            'car_name' => 'Giulietta',
            'car_info' => 'The Giulietta is a compact executive car that combines performance with Italian style.',
            'sort_order' => '2',
        ],
        [
            'id' => 3,
            'makes_id' => 1,
            'car_name' =>'Giulia',
            'car_info' => 'The Giulia is a compact executive car that combines performance with Italian style.',
            'sort_order' => '3',
        ],
        [
            'id' => 4,
            'makes_id' => 1,
            'car_name' => '147/156',
            'car_info' => '147 and 156 are compact cars that were popular in the early 2000s, known for their sporty handling and distinctive design.',
            'sort_order' => '4',
        ],
        [
            'id' => 5,
            'makes_id' => 2,
            'car_name' => '500/595/695',
            'car_info' => 'abarth500/595/695 is a high-performance version of the Fiat 500, known for its sporty design and agile handling.',
            'sort_order' => '5',
        ],
        [
            'id' => 6,
            'makes_id' => 2,
            'car_name' => 'Punto',
            'car_info' => 'Fiat Punto is a subcompact car that offers a balance of style, comfort, and efficiency.',
            'sort_order' => '6',
        ],
        [
            'id' => 7,
            'makes_id' => 2,
            'car_name' => '124spider',
            'car_info' => '124 Spider is a classic roadster that combines Italian design with sporty performance.',
            'sort_order' => '7',
        ],
        [
            'id' => 8,
            'makes_id' => 2,
            'car_name' => 'Tipo',
            'car_info' => 'Fiat Tipo is a compact car that offers a spacious interior and modern design.',
            'sort_order' => '8',
        ],
        [
            'id' => 12,
            'makes_id' => 9,
            'car_name' => 'その他',
            'car_info' => 'その他の車両は、特定のブランドやモデルに属さない車両を指します。これには、様々なメーカーの車両が含まれます。',
            'sort_order' => '9',
        ],


    ]);
    }
}
