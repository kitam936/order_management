<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class subCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sub_categories')->insert([[
            'id' => 1,
            'sub_name' => '白系',
            'sort_order' => 1
        ],
        [
            'id' => 2,
            'sub_name' => '赤系',
            'sort_order' => 2
        ],
        [
            'id' => 3,
            'sub_name' => '青系',
            'sort_order' => 3
        ],
        [
            'id' => 4,
            'sub_name' => 'シルバー系',
            'sort_order' => 4
        ],
        [
            'id' => 5,
            'sub_name' => '黒',
            'sort_order' => 5
        ],
        [
            'id' => 6,
            'sub_name' => 'イエロー系',
            'sort_order' => 6
        ],
        [
            'id' => 9,
            'sub_name' => 'その他',
            'sort_order' => 9

        ],
    ]);

    }
}
