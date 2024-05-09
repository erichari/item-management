<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::insert([
            [
                'name' => 'Admin',
                'email' => 'admin@techis.com',
                'password' => Hash::make('admin'),
                'role' => 1,
            ],
            [
                'name' => 'Test User',
                'email' => 'test@techis.com',
                'password' => Hash::make('password'),
                'role' => 2,
            ],
        ]);

        $this->call([
            TagSeeder::class,
            ItemSeeder::class,
        ]);
    }
}
