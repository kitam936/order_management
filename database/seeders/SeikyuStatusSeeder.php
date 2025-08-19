<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeikyuStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('seikyu_statuses')->insert([
            [
            'id' =>1,
            'seikyu_status_name' => '未収',
            'seikyu_status_info' => '未収',
            ],
            [
            'id' =>5,
            'seikyu_status_name' => '請求残あり',
            'seikyu_status_info' => '請求残あり',
            ],
            [
            'id' =>9,
            'seikyu_status_name' => '入金済',
            'seikyu_status_info' => '入金済',
            ],
        ]);
    }
}
