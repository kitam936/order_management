<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Shop;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shops')->insert([
            [
            'id' =>1101,
            'company_id' => 1100,
            'shop_name' => 'Stile本店',
            'shop_info' => 'Stile本店',
            'is_selling' => 1,
            'rate' => 100,
            ],
            [
            'id' => 1102,
            'company_id' => 1100,
            'shop_name' => 'Stile横浜店',
            'shop_info' => 'Stile横浜店',
            'is_selling' => 1,
            'rate' => 100,
            ],
            [
            'id' => 9999,
            'company_id' => 9999,
            'shop_name' => 'Other',
            'shop_info' => 'Other',
            'is_selling' => 1,
            'rate' => 100,
            ],
            [
            'id' => 1001,
            'company_id' => 1000,
            'shop_name' => 'Admin',
            'shop_info' => '管理者',
            'is_selling' => 1,
            'rate' => 100,
            ],




        ]);
    }
}
