<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_statuses')->insert([
            [
            'id' =>1,
            'order_status_name' => 'New',
            'order_status_info' => '新規受付',
            ],
            [
            'id' =>5,
            'order_status_name' => '一部終了',
            'order_status_info' => '一部終了',
            ],
            [
            'id' =>7,
            'order_status_name' => '最終確認中',
            'order_status_info' => '最終確認中',
            ],
            [
            'id' =>9,
            'order_status_name' => '完了',
            'order_status_info' => '完了',
            ],
            [
            'id' =>10,
            'order_status_name' => '請求済',
            'order_status_info' => '請求済',
            ],
        ]);
    }
}
