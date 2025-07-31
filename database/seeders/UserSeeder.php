<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('users')->insert([
        [
            'id' => 1,
            'name' => 'admin',
            'kana' => 'アドミン',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role_id' => 1,
            'shop_id' => 1101,
            'postcode' => '123-4567',
            'address' => '東京都千代田区1-1-1',
            'tel' => '03-1234-5678',
            'mailService' => 1
        ],
        [
            'id' => 2,
            'name' => 'cf_manager',
            'kana' => 'シーエフマネージャー',
            'email' => 'cf_manager@test.com',
            'password' => Hash::make('password123'),
            'role_id' => 3,
            'shop_id' => 1101,
            'postcode' => '123-4567',
            'address' => '東京都千代田区2-2-2',
            'tel' => '03-2345-6789',
            'mailService' => 1
        ],
        [
            'id' => 9,
            'name' => 'test1',
            'kana' => 'テスト1',
            'email' => 'test1@test.com',
            'password' => Hash::make('password123'),
            'role_id' => 5,
            'shop_id' => 1102,
            'postcode' => '123-4567',
            'address' => '東京都千代田区3-3-3',
            'tel' => '03-3456-7890',
            'mailService' => 1
        ],
        [
            'id' => 10,
            'name' => 'test2',
            'kana' => 'テスト2',
            'email' => 'test2@test.com',
            'password' => Hash::make('password123'),
            'role_id' => 3,
            'shop_id' => 1101,
            'postcode' => '123-4567',
            'address' => '東京都千代田区4-4-4',
            'tel' => '03-4567-8901',
            'mailService' => 1
        ],
        [
            'id' => 11,
            'name' => 'test3',
            'kana' => 'テスト3',
            'email' => 'test3@test.com',
            'password' => Hash::make('password123'),
            'role_id' => 5,
            'shop_id' => 1102,
            'postcode' => '123-4567',
            'address' => '東京都千代田区5-5-5',
            'tel' => '03-4567-8901',
            'mailService' => 1
        ],
        [
            'id' => 12,
            'name' => 'test4',
            'kana' => 'テスト4',
            'email' => 'test4@test.com',
            'password' => Hash::make('password123'),
            'role_id' => 99,
            'shop_id' => 1101,
            'postcode' => '123-4567',
            'address' => '東京都千代田区6-6-6',
            'tel' => '03-4567-8901',
            'mailService' => 1
        ],
        [
            'id' => 13,
            'name' => 'test5',
            'kana' => 'テスト5',
            'email' => 'test5@test.com',
            'password' => Hash::make('password123'),
            'role_id' => 99,
            'shop_id' => 1101,
            'postcode' => '123-4567',
            'address' => '東京都千代田区7-7-7',
            'tel' => '03-4567-8901',
            'mailService' => 1
        ],
        [
            'id' => 14,
            'name' => 'test6',
            'kana' => 'テスト6',
            'email' => 'test6@test.com',
            'password' => Hash::make('password123'),
            'role_id' => 99,
            'shop_id' => 1102,
            'postcode' => '123-4567',
            'address' => '東京都千代田区8-8-8',
            'tel' => '03-4567-8901',
            'mailService' => 1
        ],

    ]);
    }
}
