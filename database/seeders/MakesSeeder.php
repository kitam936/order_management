<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MakesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('makes')->insert([[
            'id' => 1,
            'makes_name' => 'AlfaRomeo',
            'makes_info' => 'AlfaRomeo',
            'sort' => '1',
        ],
        [
            'id' => 2,
            'makes_name' => 'Abarth',
            'makes_info' => 'Abarth',
            'sort' => '2',
        ],
        [
            'id' => 3,
            'makes_name' => 'Fiat',
            'makes_info' => 'Fiat',
            'sort' => '3',
        ],
        [
            'id' => 4,
            'makes_name' => 'Renault',
            'makes_info' => 'Renault',
            'sort' => '4',
        ],
        [
            'id' => 5,
            'makes_name' => 'Peugeot',
            'makes_info' => 'Peugeot',
            'sort' => '5',
        ],
        [
            'id' => 6,
            'makes_name' => 'Alpine',
            'makes_info' => 'Alpine',
            'sort' => '6',
        ],

        [
            'id' => 9,
            'makes_name' => 'Others',
            'makes_info' => 'Others',
            'sort' => '9',
        ]


    ]);
    }
}
