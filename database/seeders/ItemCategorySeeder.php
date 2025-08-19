<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('item_categories')->insert([[
            'id' => 101,
            'item_category_name'=> '車両',
            'item_category_info' => '車両のサブカテゴリーです。',
            'sort_order' => 1
        ],
        [
            'id' => 102,
            'item_category_name'=> 'エンジン係パーツ',
            'item_category_info' => 'エンジン係パーツのサブカテゴリーです。',
            'sort_order' => 2
        ],
        [
            'id' => 103,
            'item_category_name'=> '駆動系',
            'item_category_info' => '駆動系のサブカテゴリーです。',
            'sort_order' => 3
        ],
        [
            'id' => 104,
            'item_category_name'=> '足回り係',
            'item_category_info' => '足回り係のサブカテゴリーです。',
            'sort_order' => 4
        ],
        [
            'id' => 105,
            'item_category_name'=> '外装系',
            'item_category_info' => '外装系のサブカテゴリーです。',
            'sort_order' => 5
        ],
        [
            'id' => 106,
            'item_category_name'=> '内装系',
            'item_category_info' => '内装系のサブカテゴリーです。',
            'sort_order' => 6
        ],
        [
            'id' => 107,
            'item_category_name'=> '電装系',
            'item_category_info' => '電装系のサブカテゴリーです。',
            'sort_order' => 7
        ],
        [
            'id' => 109,
            'item_category_name'=> 'その他',
            'item_category_info' => 'その他のサブカテゴリーです。',
            'sort_order' => 8
        ],
        [
            'id' => 199,
            'item_category_name'=> '車検',
            'item_category_info' => '車検のサブカテゴリーです。',
            'sort_order' => 9
        ],

    ]);

    }
}
