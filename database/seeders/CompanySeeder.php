<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{

    public function run()
    {
        DB::table('companies')->insert([
            [
            'id' => 1100,
            'co_name' => 'Stile',
            'co_info' => 'Stile',
            ],
            [
            'id' => 1200,
            'co_name' => 'Dijon',
            'co_info' => 'Dijon',
            ],
            [
            'id' => 9999,
            'co_name' => 'User',
            'co_info' => 'User',
            ],
            [
            'id' => 1000,
            'co_name' => '管理者',
            'co_info' => '管理者',
            ],



        ]);
    }
}
