<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PayMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pay_methods')->insert([[
            'id' => 1,
            'pay_method_name' => '現金',
            'pay_method_info' => '現金',
        ],
        [
            'id' => 2,
            'pay_method_name' => 'クレジットカード',
            'pay_method_info' => 'クレジットカード',
        ],
        [
            'id' => 3,
            'pay_method_name' => '振込',
            'pay_method_info' => '振込',
        ],
        [
            'id' => 4,
            'pay_method_name' => 'QR決済',
            'pay_method_info' => 'QR決済',
        ],
        [
            'id' => 99,
            'pay_method_name' => 'その他',
            'pay_method_info' => 'その他',
        ],

    ]);
    }
}
