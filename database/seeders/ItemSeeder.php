<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('items')->insert([
            [
            'id' => 10300001,
            'item_category_id'  => 103,
            'car_category_id'=> 1,
            'prod_code'=> 'AB1234',
            'item_name' => 'ドライブシャフト',
            'item_price' => 40000,
            'item_cost' => 32000,
            'item_info' => 'ドライブシャフトは、車両の駆動系においてエンジンからの動力を車輪に伝える重要な部品です。高い耐久性と信頼性を持ち、スムーズな走行を実現します。',
            'item_image' => '',
        ],
        [
            'id' => 10300002,
            'item_category_id'  => 103,
            'car_category_id'=>7,
            'prod_code'=> 'AB1235',
            'item_name' => 'ハブベアリングFR',
            'item_price' => 25000,
            'item_cost' => 20000,
            'item_info' => 'ハブベアリングは、車両のホイールとサスペンションを接続する重要な部品で、スムーズな回転を提供します。高品質な素材で作られ、長寿命を実現しています。',
            'item_image' => '',
        ],
        [
            'id' => 10300003,
            'item_category_id'  => 103,
            'car_category_id'=> 7,
            'prod_code'=> 'AB1236',
            'item_name' => 'ハブベアリングRear',
            'item_price' => 15000,
            'item_cost' => 12000,
            'item_info' => 'ハブベアリングは、車両のホイールとサスペンションを接続する重要な部品で、スムーズな回転を提供します。高品質な素材で作られ、長寿命を実現しています。',
            'item_image' => '',
        ],
        [
            'id' => 10300004,
            'item_category_id'  => 103,
            'car_category_id'=> 7,
            'prod_code'=> 'AB1237',
            'item_name' => 'クラッチディスク',
            'item_price' => 80000,
            'item_cost' => 64000,
            'item_info' => 'クラッチディスクは、車両のトランスミッションシステムにおいてエンジンの動力を車輪に伝える重要な部品です。高い耐久性と信頼性を持ち、スムーズな変速を実現します。',
            'item_image' => '',
        ],



    ]);
    }
}
