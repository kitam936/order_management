<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RoleSeeder::class,

            CompanySeeder::class,
            ShopSeeder::class,
            UserSeeder::class,
            // BrandSeeder::class,
            // UnitSeeder::class,

        ]);
        User::factory(300)->create();

        // Order::factory(100)->create();

        // User::factory(300)->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
