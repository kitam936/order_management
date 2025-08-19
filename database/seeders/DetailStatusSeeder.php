<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('detail_statuses')->insert([
            [
            'id' =>1,
            'detail_status_name' => 'New',
            'detail_status_info' => '新規受付',
            ],
            [
            'id' =>3,
            'detail_status_name' => '作業中',
            'detail_status_info' => '作業中',
            ],
            [
            'id' =>7,
            'detail_status_name' => '最終確認中',
            'detail_status_info' => '最終確認中',
            ],
            [
            'id' =>9,
            'detail_status_name' => '完了',
            'detail_status_info' => '完了',
            ],

        ]);
    }
}
